<?php

declare(strict_types=1);

@_API_EXEC === 1 or exit('Restricted access.');

use Skaut\HandbookAPI\v1_0\Endpoint;
use Skaut\HandbookAPI\v1_0\Role;
use Skautis\Skautis;

$refreshEndpoint = new Endpoint;

$refreshLogin = function (Skautis $skautis) use ($CONFIG): array {
    $dateLogout = $skautis->UserManagement->LoginUpdateRefresh(['ID' => $_COOKIE['skautis_token']])->DateLogout;
    $timeout = DateTime::createFromFormat(
        'Y-m-d\TH:i:s.u',
        $dateLogout,
        new DateTimeZone('Europe/Prague')
    );
    if (! $timeout) {
        $timeout = (new \DateTime('now', new \DateTimeZone('Europe/Prague')))->add(new \DateInterval('P10M'));
    }
    $timeout = $timeout->format('U');
    setcookie('skautis_timeout', $timeout, intval($timeout), '/', $CONFIG->cookieuri, true, false);
    $_COOKIE['skautis_timeout'] = $timeout;

    return ['status' => 200];
};
$refreshEndpoint->setListMethod(new Role('user'), $refreshLogin);
