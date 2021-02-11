<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');

require_once($CONFIG->basepath . '/v0.9/endpoints/userRoleEndpoint.php');
require_once($CONFIG->basepath . '/v0.9/endpoints/userGroupEndpoint.php');

use Skautis\Skautis;

use Skaut\HandbookAPI\v0_9\Database;
use Skaut\HandbookAPI\v0_9\Endpoint;
use Skaut\HandbookAPI\v0_9\Helper;
use Skaut\HandbookAPI\v0_9\Role;
use Skaut\HandbookAPI\v0_9\User;
use Skaut\HandbookAPI\v0_9\Exception\InvalidArgumentTypeException;
use Skaut\HandbookAPI\v0_9\Exception\MissingArgumentException;
use Skaut\HandbookAPI\v0_9\Exception\NotFoundException;

$userEndpoint = new Endpoint();
$userEndpoint->addSubEndpoint('role', $userRoleEndpoint);
$userEndpoint->addSubEndpoint('group', $userGroupEndpoint);


function constructSelectSQL(Skautis $skautis, bool $roleSelect, bool $groupSelect) : string
{
    $role = Role::get($skautis->UserManagement->LoginDetail()->ID_Person);

    $innerSQL = '';
    if (Role::compare($role, new Role('administrator')) >= 0) {
        $innerSQL .= ', \'editor\'';
    }
    if (Role::compare($role, new Role('superuser')) === 0) {
        $innerSQL .= ', \'administrator\', \'superuser\'';
    }

    $roleSQL = $roleSelect ? ' AND users.role = :role ' : '';
    $groupSQL = $groupSelect ? 'AND users_in_groups.group_id = :group_id ' : '';

    $selectSQL = <<<SQL
SELECT DISTINCT SQL_CALC_FOUND_ROWS `users`.`id`, `users`.`name`, `users`.`role`
FROM `users`
LEFT JOIN `users_in_groups` ON `users`.`id` = `users_in_groups`.`user_id`
WHERE `users`.`name` LIKE CONCAT('%', :name, '%') AND `users`.`role` IN ('guest', 'user'
SQL
    . $innerSQL . <<<SQL
)
SQL
    . $roleSQL . $groupSQL . <<<SQL
ORDER BY `users`.`name`
LIMIT :start, :per_page;
SQL;
    return $selectSQL;
}

$listUsers = function (Skautis $skautis, array $data) : array {
    $selectSQL = constructSelectSQL($skautis, isset($data['role']), isset($data['group']));
    $countSQL = <<<SQL
SELECT FOUND_ROWS();
SQL;
    $groupSQL = <<<SQL
SELECT `group_id`
FROM `users_in_groups`
WHERE `user_id` = :user_id;
SQL;
    $groupCheckSQL = <<<SQL
SELECT 1
FROM `groups`
WHERE `id` = :id
LIMIT 1;
SQL;

    $searchName = '';
    if (isset($data['name'])) {
        $searchName = $data['name'];
    }
    $per_page = 25;
    if (isset($data['per-page'])) {
        $per_page = ctype_digit($data['per-page']) ? intval($data['per-page']) : null;
        if ($per_page === null) {
            throw new InvalidArgumentTypeException('per-page', ['Integer']);
        }
    }
    $start = 0;
    if (isset($data['page'])) {
        $start = ctype_digit($data['page']) ? ($per_page * (intval($data['page']) - 1)) : null;
        if ($start === null) {
            throw new InvalidArgumentTypeException('page', ['Integer']);
        }
    }

    $db = new Database();
    if (isset($data['group'])) {
        $group_id = Helper::parseUuid($data['group'], 'group')->getBytes();
        $db->prepare($groupCheckSQL);
        $db->bindParam(':id', $group_id, PDO::PARAM_STR);
        $db->execute();
        $db->fetchRequire('group');
    }

    $db->prepare($selectSQL);
    $db->bindParam(':name', $searchName, PDO::PARAM_STR);
    if (isset($data['role'])) {
        if (!in_array($data['role'], ['user', 'editor', 'administrator', 'superuser'])) {
            throw new NotFoundException('role');
        }
        $role = (new Role($data['role']))->__toString();
        $db->bindParam(':role', $role, PDO::PARAM_STR);
    }
    if (isset($data['group'])) {
        $db->bindParam(':group_id', $group_id, PDO::PARAM_STR);
    }
    $db->bindParam(':start', $start, PDO::PARAM_INT);
    $db->bindParam(':per_page', $per_page, PDO::PARAM_INT);
    $db->execute();
    $userResult = $db->fetchAll();

    $db->prepare($countSQL);
    $db->execute();
    $count = 0;
    $db->bindColumn(1, $count);
    $db->fetchRequire('users');

    $users = [];
    foreach ($userResult as $row) {
        $newUser = new User(intval($row['id']), $row['name'], $row['role']);
        $users[] = $newUser;

        $db2 = new Database();
        $db2->prepare($groupSQL);
        $db2->bindParam(':user_id', $row['id'], PDO::PARAM_STR);
        $db2->execute();
        $group = '';
        $db2->bindColumn('group_id', $group);
        while ($db2->fetch()) {
            $newUser->addGroup($group);
        }
    }

    return ['status' => 200, 'response' => ['count' => $count, 'users' => $users]];
};
$userEndpoint->setListMethod(new Role('editor'), $listUsers);

$addUser = function (Skautis $skautis, array $data) : array {
    if (!isset($data['id'])) {
        throw new MissingArgumentException(MissingArgumentException::POST, 'id');
    }
    $id = ctype_digit($data['id']) ? intval($data['id']) : null;
    if ($id === null) {
        throw new InvalidArgumentTypeException('id', ['Integer']);
    }
    if (!isset($data['name'])) {
        throw new MissingArgumentException(MissingArgumentException::POST, 'name');
    }
    $name = $data['name'];

    $SQL = <<<SQL
INSERT INTO `users` (`id`, `name`)
VALUES (:id, :name)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);
SQL;

    $db = new Database();
    $db->prepare($SQL);
    $db->bindParam(':id', $id, PDO::PARAM_INT);
    $db->bindParam(':name', $name, PDO::PARAM_STR);
    $db->execute();
    return ['status' => 200];
};
$userEndpoint->setAddMethod(new Role('editor'), $addUser);
