<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9;

@_API_EXEC === 1 or die('Restricted access.');

use \Skautis\Skautis;

use Skaut\HandbookAPI\v0_9\Exception\RoleException;

function roleTry(callable $callback, bool $hardCheck, Role $requiredRole)
{
    $_API_SECRETS_EXEC = 1;
    $SECRETS = require($_SERVER['DOCUMENT_ROOT'] . '/api-secrets.php');
    if (Role_cmp($requiredRole, new Role('guest')) === 0) {
        return $callback(Skautis::getInstance($SECRETS->skautis_app_id, $SECRETS->skautis_test_mode));
    }
    if (Role_cmp($requiredRole, new Role('user')) === 0) {
        return skautisTry($callback, $hardCheck);
    }
    $safeCallback = function (Skautis $skautis) use ($callback, $requiredRole) {
        $role = Role::get($skautis->UserManagement->LoginDetail()->ID_Person);
        if (Role_cmp($role, $requiredRole) >= 0) {
            return $callback($skautis);
        } else {
            throw new RoleException();
        }
    };
    return skautisTry($safeCallback, $hardCheck);
}
