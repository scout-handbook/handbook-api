<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
require_once($CONFIG->basepath . '/vendor/autoload.php');
require_once($CONFIG->basepath . '/v0.9/internal/Role.php');


use Skaut\HandbookAPI\v0_9\Database;
use Skaut\HandbookAPI\v0_9\Endpoint;
use Skaut\HandbookAPI\v0_9\Exception\InvalidArgumentTypeException;
use Skaut\HandbookAPI\v0_9\Exception\MissingArgumentException;
use Skaut\HandbookAPI\v0_9\Exception\RoleException;

$userRoleEndpoint = new Endpoint();

$updateUserRole = function (Skautis\Skautis $skautis, array $data) : array {
    $checkRole = function (HandbookAPI\Role $my_role, HandbookAPI\Role $role) : void {
        if ((HandbookAPI\Role_cmp($my_role, new HandbookAPI\Role('administrator')) === 0) and
            (HandbookAPI\Role_cmp($role, new HandbookAPI\Role('administrator')) >= 0)) {
            throw new RoleException();
        }
    };

    $selectSQL = <<<SQL
SELECT role
FROM users
WHERE id = :id;
SQL;
    $updateSQL = <<<SQL
UPDATE users
SET role = :role
WHERE id = :id
LIMIT 1;
SQL;

    $id = ctype_digit($data['parent-id']) ? intval($data['parent-id']) : null;
    if ($id === null) {
        throw new InvalidArgumentTypeException('id', ['Integer']);
    }
    if (!isset($data['role'])) {
        throw new MissingArgumentException(MissingArgumentException::POST, 'role');
    }
    $new_role = new HandbookAPI\Role($data['role']);

    $my_role = HandbookAPI\getRole($skautis->UserManagement->LoginDetail()->ID_Person);
    $checkRole($my_role, $new_role);

    $db = new Database();
    $db->prepare($selectSQL);
    $db->bindParam(':id', $id, PDO::PARAM_INT);
    $db->execute();
    $old_role = '';
    $db->bindColumn('role', $old_role);
    $db->fetchRequire('user');
    $checkRole($my_role, new HandbookAPI\Role($old_role));

    $new_role_str = $new_role->__toString();
    $db->prepare($updateSQL);
    $db->bindParam(':role', $new_role_str, PDO::PARAM_STR);
    $db->bindParam(':id', $id, PDO::PARAM_INT);
    $db->execute();
    return ['status' => 200];
};
$userRoleEndpoint->setUpdateMethod(new HandbookAPI\Role('administrator'), $updateUserRole);
