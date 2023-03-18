<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

use Skautis\Skautis;

use Skaut\HandbookAPI\v1_0\Database;
use Skaut\HandbookAPI\v1_0\Endpoint;
use Skaut\HandbookAPI\v1_0\Role;
use Skaut\HandbookAPI\v1_0\Exception\InvalidArgumentTypeException;
use Skaut\HandbookAPI\v1_0\Exception\MissingArgumentException;
use Skaut\HandbookAPI\v1_0\Exception\RoleException;

$userRoleEndpoint = new Endpoint();

$updateUserRole = function (Skautis $skautis, array $data) : array {
    $checkRole = function (Role $my_role, Role $role) : void {
        if ((Role::compare($my_role, new Role('administrator')) === 0) and
            (Role::compare($role, new Role('administrator')) >= 0)
        ) {
            throw new RoleException();
        }
    };

    $selectSQL = <<<SQL
SELECT `role`
FROM `users`
WHERE `id` = :id;
SQL;
    $updateSQL = <<<SQL
UPDATE `users`
SET `role` = :role
WHERE `id` = :id
LIMIT 1;
SQL;

    $id = ctype_digit($data['parent-id']) ? intval($data['parent-id']) : null;
    if ($id === null) {
        throw new InvalidArgumentTypeException('id', ['Integer']);
    }
    if (!isset($data['role'])) {
        throw new MissingArgumentException(MissingArgumentException::POST, 'role');
    }
    $new_role = new Role($data['role']);

    $my_role = Role::get($skautis->UserManagement->LoginDetail()->ID_Person);
    $checkRole($my_role, $new_role);

    $db = new Database();
    $db->prepare($selectSQL);
    $db->bindParam(':id', $id, PDO::PARAM_INT);
    $db->execute();
    $old_role = '';
    $db->bindColumn('role', $old_role);
    $db->fetchRequire('user');
    $checkRole($my_role, new Role($old_role));

    $new_role_str = $new_role->__toString();
    $db->prepare($updateSQL);
    $db->bindParam(':role', $new_role_str, PDO::PARAM_STR);
    $db->bindParam(':id', $id, PDO::PARAM_INT);
    $db->execute();
    return ['status' => 200];
};
$userRoleEndpoint->setUpdateMethod(new Role('administrator'), $updateUserRole);
