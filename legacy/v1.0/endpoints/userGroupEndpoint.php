<?php

declare(strict_types=1);

@_API_EXEC === 1 or exit('Restricted access.');

use Skaut\HandbookAPI\v1_0\Database;
use Skaut\HandbookAPI\v1_0\Endpoint;
use Skaut\HandbookAPI\v1_0\Exception\InvalidArgumentTypeException;
use Skaut\HandbookAPI\v1_0\Exception\RoleException;
use Skaut\HandbookAPI\v1_0\Helper;
use Skaut\HandbookAPI\v1_0\Role;
use Skautis\Skautis;

$userGroupEndpoint = new Endpoint;

$updateUserGroup = function (Skautis $skautis, array $data): array {
    $checkRole = function (Role $my_role, Role $role): void {
        if (
            (Role::compare($my_role, new Role('administrator')) === 0) and
            (Role::compare($role, new Role('administrator')) >= 0)
        ) {
            throw new RoleException;
        }
    };

    $selectSQL = <<<'SQL'
SELECT `role`
FROM `users`
WHERE `id` = :id;
SQL;
    $deleteSQL = <<<'SQL'
DELETE FROM `users_in_groups`
WHERE `user_id` = :user_id;
SQL;
    $insertSQL = <<<'SQL'
INSERT INTO `users_in_groups` (`user_id`, `group_id`)
VALUES (:user_id, :group_id);
SQL;

    $id = ctype_digit($data['parent-id']) ? intval($data['parent-id']) : null;
    if ($id === null) {
        throw new InvalidArgumentTypeException('id', ['Integer']);
    }
    $groups = [];
    if (isset($data['group'])) {
        if (is_array($data['group'])) {
            foreach ($data['group'] as $group) {
                $groups[] = Helper::parseUuid($group, 'group')->getBytes();
            }
        } else {
            $groups[] = Helper::parseUuid($data['group'], 'group')->getBytes();
        }
    }

    $my_role = Role::get($skautis->UserManagement->LoginDetail()->ID_Person);

    $db = new Database;
    $db->beginTransaction();

    $db->prepare($selectSQL);
    $db->bindParam(':id', $id, PDO::PARAM_INT);
    $db->execute();
    $other_role = '';
    $db->bindColumn('role', $other_role);
    $db->fetchRequire('user');
    $checkRole($my_role, new Role($other_role));

    $db->prepare($deleteSQL);
    $db->bindParam(':user_id', $id, PDO::PARAM_STR);
    $db->execute();

    $db->prepare($insertSQL);
    foreach ($groups as $group) {
        $db->bindParam(':user_id', $id, PDO::PARAM_STR);
        $db->bindParam(':group_id', $group, PDO::PARAM_STR);
        $db->execute('user or group');
    }

    $db->endTransaction();

    return ['status' => 200];
};
$userGroupEndpoint->setUpdateMethod(new Role('administrator'), $updateUserGroup);
