<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

use Skautis\Skautis;

use Skaut\HandbookAPI\v0_9\Endpoint;
use Skaut\HandbookAPI\v0_9\Role;

$refreshEndpoint = new Endpoint();

$refreshLogin = function (Skautis $skautis) use ($CONFIG) : array {
    $dateLogout = $skautis->UserManagement->LoginUpdateRefresh(['ID' => $_COOKIE['skautis_token']])->DateLogout;
    $timeout = DateTime::createFromFormat(
        'Y-m-d\TH:i:s.u',
        $dateLogout,
        new DateTimeZone('Europe/Prague')
    );
    if (!$timeout) {
        $timeout = (new \DateTime('now', new \DateTimeZone('Europe/Prague')))->add(new \DateInterval('10M'));
    }
    $timeout = $timeout->format('U');
    setcookie('skautis_timeout', $timeout, intval($timeout), "/", $CONFIG->cookieuri, true, false);
    $_COOKIE['skautis_timeout'] = $timeout;
    return ['status' => 200];
};
$refreshEndpoint->setListMethod(new Role('user'), $refreshLogin);
