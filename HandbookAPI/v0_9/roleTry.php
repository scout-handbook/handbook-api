<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9;

@_API_EXEC === 1 or die('Restricted access.');

require($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
require_once($CONFIG->basepath . '/vendor/autoload.php');
require_once($CONFIG->basepath . '/v0.9/internal/Role.php');

require_once($CONFIG->basepath . '/v0.9/internal/exceptions/RoleException.php');

use \Skautis\Skautis;

function roleTry(callable $callback, bool $hardCheck, \HandbookAPI\Role $requiredRole)
{
    $_API_SECRETS_EXEC = 1;
    $SECRETS = require($_SERVER['DOCUMENT_ROOT'] . '/api-secrets.php');
    if (\HandbookAPI\Role_cmp($requiredRole, new \HandbookAPI\Role('guest')) === 0) {
        return $callback(Skautis::getInstance($SECRETS->skautis_app_id, $SECRETS->skautis_test_mode));
    }
    if (\HandbookAPI\Role_cmp($requiredRole, new \HandbookAPI\Role('user')) === 0) {
        return skautisTry($callback, $hardCheck);
    }
    $safeCallback = function (Skautis $skautis) use ($callback, $requiredRole) {
        $role = \HandbookAPI\getRole($skautis->UserManagement->LoginDetail()->ID_Person);
        if (\HandbookAPI\Role_cmp($role, $requiredRole) >= 0) {
            return $callback($skautis);
        } else {
            throw new \HandbookAPI\RoleException();
        }
    };
    return skautisTry($safeCallback, $hardCheck);
}
