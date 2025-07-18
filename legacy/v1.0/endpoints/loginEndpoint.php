<?php

declare(strict_types=1);

@_API_EXEC === 1 or exit('Restricted access.');

require_once $_SERVER['DOCUMENT_ROOT'].'/api-config.php';

require_once $CONFIG->basepath.'/v1.0/endpoints/accountEndpoint.php';

use Skaut\HandbookAPI\v1_0\Endpoint;
use Skaut\HandbookAPI\v1_0\Role;
use Skautis\Skautis;

$loginEndpoint = new Endpoint;

$loginUser = function (Skautis $skautis, array $data) use ($CONFIG, $accountEndpoint): void {
    $startsWith = function (string $haystack, string $needle): bool {
        return mb_substr($haystack, 0, mb_strlen($needle)) === $needle;
    };
    $_API_SECRETS_EXEC = 1;
    $SECRETS = require $_SERVER['DOCUMENT_ROOT'].'/api-secrets.php';
    $ISprefix = $SECRETS->skautis_test_mode ? 'https://test-is.skaut.cz' : 'https://is.skaut.cz';

    // Back from SkautIS, finish logging in and redirect back
    if (isset($_SERVER['HTTP_REFERER']) and $startsWith($_SERVER['HTTP_REFERER'], $ISprefix)) {
        $token = $data['skautIS_Token'];
        $timeout = DateTime::createFromFormat(
            'j. n. Y H:i:s',
            $data['skautIS_DateLogout'],
            new DateTimeZone('Europe/Prague')
        );
        if (! $timeout) {
            $timeout = (new \DateTime('now', new \DateTimeZone('Europe/Prague')))->add(new \DateInterval('PT10M'));
        }
        $timeout = $timeout->format('U');

        setcookie('skautis_token', $token, intval($timeout), '/', $CONFIG->cookieuri, true, true);
        setcookie('skautis_timeout', $timeout, intval($timeout), '/', $CONFIG->cookieuri, true, false);
        $_COOKIE['skautis_token'] = $token;
        $_COOKIE['skautis_timeout'] = $timeout;

        $accountEndpoint->call('POST', new Role('user'), []);

        $redirect = $CONFIG->baseuri;
        if (isset($_COOKIE['return-uri'])) {
            $redirect = $_COOKIE['return-uri'];
            setcookie('return-uri', '', time() - 3600, '/', $CONFIG->cookieuri, true, true);
            unset($_COOKIE['return-uri']);
        }
        if ($startsWith($redirect, 'http://')) {
            $redirect = 'https://'.mb_substr($redirect, 7);
        } elseif (! $startsWith($redirect, 'https://')) {
            if (! $startsWith($redirect, '/')) {
                $redirect = '/'.$redirect;
            }
            $redirect = $CONFIG->baseuri.$redirect;
        }
        header('Location: '.$redirect);
        exit();
    }

    // Called by user, set up and redirect to SkautIS
    if (isset($data['return-uri'])) {
        $redirect = $data['return-uri'];
    } elseif (isset($_SERVER['HTTP_REFERER']) and $startsWith($_SERVER['HTTP_REFERER'], $CONFIG->baseuri)) {
        $redirect = mb_substr($_SERVER['HTTP_REFERER'], mb_strlen($CONFIG->baseuri));
    }
    if (isset($redirect)) {
        setcookie('return-uri', $redirect, time() + 600, '/', $CONFIG->cookieuri, true, true);
        $_COOKIE['return-uri'] = $redirect;
    }
    header('Location: '.$skautis->getLoginUrl());
    exit();
};
$loginEndpoint->setListMethod(new Role('guest'), $loginUser);
$loginEndpoint->setAddMethod(new Role('guest'), $loginUser);
