<?php

declare(strict_types=1);

@_API_EXEC === 1 or exit('Restricted access.');

use Skaut\HandbookAPI\v1_0\Endpoint;
use Skaut\HandbookAPI\v1_0\Role;
use Skautis\Skautis;

$logoutEndpoint = new Endpoint;

$logoutUser = function (Skautis $skautis, array $data) use ($CONFIG): void {
    $startsWith = function (string $haystack, string $needle): bool {
        return mb_substr($haystack, 0, mb_strlen($needle)) === $needle;
    };
    $_API_SECRETS_EXEC = 1;
    $SECRETS = require $_SERVER['DOCUMENT_ROOT'].'/api-secrets.php';
    $ISprefix = $SECRETS->skautis_test_mode ? 'https://test-is.skaut.cz' : 'https://is.skaut.cz';

    // Called by a user, set up and redirect to SkautIS
    if (isset($_SERVER['HTTP_REFERER']) and ! $startsWith($_SERVER['HTTP_REFERER'], $ISprefix)) {
        if (isset($_COOKIE['skautis_token'])) {
            if (isset($data['return-uri']) and $data['return-uri'] != '') {
                setcookie('return-uri', $data['return-uri'], time() + 30, '/', $CONFIG->cookieuri, true, true);
                $_COOKIE['return-uri'] = $data['return-uri'];
            }

            $dateLogout = (new \DateTime('now', new \DateTimeZone('Europe/Prague')))
                ->add(new \DateInterval('PT60S'))
                ->format('j. n. Y H:i:s');
            $reconstructedPost = [
                'skautIS_Token' => $_COOKIE['skautis_token'],
                'skautIS_IDRole' => '',
                'skautIS_IDUnit' => '',
                'skautIS_DateLogout' => $dateLogout,
            ];
            $skautis->setLoginData($reconstructedPost);
            header('Location: '.$skautis->getLogoutUrl());
            exit();
        } else {
            if (isset($_COOKIE['return-uri'])) {
                header('Location: '.$_COOKIE['return-uri']);
                exit();
            }
            header('Location: '.$CONFIG->baseuri);
            exit();
        }
    }

    // Back from SkautIS, finish logging out and redirect back
    setcookie('skautis_token', '', time() - 3600, '/', $CONFIG->cookieuri, true, true);
    setcookie('skautis_timeout', '', time() - 3600, '/', $CONFIG->cookieuri, true, true);
    unset($_COOKIE['skautis_token']);
    unset($_COOKIE['skautis_timeout']);

    if (isset($_COOKIE['return-uri'])) {
        $redirect = $_COOKIE['return-uri'];
        setcookie('return-uri', '', time() - 3600, '/', $CONFIG->cookieuri, true, true);
        unset($_COOKIE['return-uri']);
        header('Location: '.$redirect);
        exit();
    }
    header('Location: '.$CONFIG->baseuri);
    exit();
};
$logoutEndpoint->setListMethod(new Role('guest'), $logoutUser);
$logoutEndpoint->setAddMethod(new Role('guest'), $logoutUser);
