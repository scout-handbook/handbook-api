<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;
use Skautis\Skautis;

use Skaut\HandbookAPI\v0_9\Database;
use Skaut\HandbookAPI\v0_9\Endpoint;
use Skaut\HandbookAPI\v0_9\Group;
use Skaut\HandbookAPI\v0_9\Helper;
use Skaut\HandbookAPI\v0_9\Role;
use Skaut\HandbookAPI\v0_9\Exception\MissingArgumentException;
use Skaut\HandbookAPI\v0_9\Exception\NotFoundException;
use Skaut\HandbookAPI\v0_9\Exception\RefusedException;

$groupEndpoint = new Endpoint();

$listGroups = function () : array {
    $selectSQL = <<<SQL
SELECT id, name
FROM groups;
SQL;
    $countSQL = <<<SQL
SELECT COUNT(*) FROM users_in_groups
WHERE group_id = :group_id;
SQL;

    $db = new Database();
    $db->prepare($selectSQL);
    $db->execute();
    $id = '';
    $name = '';
    $db->bindColumn('id', $id);
    $db->bindColumn('name', $name);
    $groups = [];
    while ($db->fetch()) {
        $db2 = new Database();
        $db2->prepare($countSQL);
        $db2->bindParam(':group_id', $id, PDO::PARAM_STR);
        $db2->execute();
        $count = '';
        $db2->bindColumn(1, $count);
        $db2->fetchRequire('group');
        $groups[] = new Group($id, strval($name), intval($count));
    }
    return ['status' => 200, 'response' => $groups];
};
$groupEndpoint->setListMethod(new Role('editor'), $listGroups);

$addGroup = function (Skautis $skautis, array $data) : array {
    $SQL = <<<SQL
INSERT INTO groups (id, name)
VALUES (:id, :name);
SQL;

    if (!isset($data['name'])) {
        throw new MissingArgumentException(MissingArgumentException::POST, 'name');
    }
    $name = $data['name'];
    $uuid = Uuid::uuid4()->getBytes();

    $db = new Database();
    $db->prepare($SQL);
    $db->bindParam(':id', $uuid, PDO::PARAM_STR);
    $db->bindParam(':name', $name, PDO::PARAM_STR);
    $db->execute();
    return ['status' => 201];
};
$groupEndpoint->setAddMethod(new Role('administrator'), $addGroup);

$updateGroup = function (Skautis $skautis, array $data) : array {
    $updateSQL = <<<SQL
UPDATE groups
SET name = :name
WHERE id = :id
LIMIT 1;
SQL;

    $id = Helper::parseUuid($data['id'], 'group')->getBytes();
    if (!isset($data['name'])) {
        throw new MissingArgumentException(MissingArgumentException::POST, 'name');
    }
    $name = $data['name'];
    
    $db = new Database();
    $db->beginTransaction();

    $db->prepare($updateSQL);
    $db->bindParam(':name', $name, PDO::PARAM_STR);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->execute();

    $db->endTransaction();
    return ['status' => 200];
};
$groupEndpoint->setUpdateMethod(new Role('administrator'), $updateGroup);

$deleteGroup = function (Skautis $skautis, array $data) : array {
    $deleteLessonsSQL = <<<SQL
DELETE FROM groups_for_lessons
WHERE group_id = :group_id;
SQL;
    $deleteUsersSQL = <<<SQL
DELETE FROM users_in_groups
WHERE group_id = :group_id;
SQL;
    $deleteSQL = <<<SQL
DELETE FROM groups
WHERE id = :id
LIMIT 1;
SQL;
    
    $id = Helper::parseUuid($data['id'], 'group');
    if ($id == Uuid::fromString('00000000-0000-0000-0000-000000000000')) {
        throw new RefusedException();
    }
    $id = $id->getBytes();

    $db = new Database();
    $db->beginTransaction();

    $db->prepare($deleteLessonsSQL);
    $db->bindParam(':group_id', $id, PDO::PARAM_STR);
    $db->execute();

    $db->prepare($deleteUsersSQL);
    $db->bindParam(':group_id', $id, PDO::PARAM_STR);
    $db->execute();

    $db->prepare($deleteSQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->execute();

    if ($db->rowCount() != 1) {
        throw new NotFoundException("group");
    }

    $db->endTransaction();
    return ['status' => 200];
};
$groupEndpoint->setDeleteMethod(new Role('administrator'), $deleteGroup);
