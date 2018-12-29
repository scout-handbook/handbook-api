<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
require_once($CONFIG->basepath . '/vendor/autoload.php');
require_once($CONFIG->basepath . '/v0.9/internal/Endpoint.php');
require_once($CONFIG->basepath . '/v0.9/internal/Role.php');

$logoutEndpoint = new HandbookAPI\Endpoint();

$logoutUser = function (Skautis\Skautis $skautis, array $data) use ($CONFIG) : void {
    $startsWith = function (string $haystack, string $needle) : bool {
        return (mb_substr($haystack, 0, mb_strlen($needle)) === $needle);
    };
    $_API_SECRETS_EXEC = 1;
    $SECRETS = require($_SERVER['DOCUMENT_ROOT'] . '/api-secrets.php');
    $ISprefix = $SECRETS->skautis_test_mode ? 'https://test-is.skaut.cz/Login' : 'https://is.skaut.cz/Login';

    // Called by a user, set up and redirect to SkautIS
    if (isset($_SERVER['HTTP_REFERER']) and $startsWith($_SERVER['HTTP_REFERER'], $ISprefix)) {
        if (isset($data['return-uri']) and $data['return-uri'] != '') {
            setcookie('return-uri', $data['return-uri'], time() + 30, "/", $CONFIG->cookieuri, true, true);
            $_COOKIE['return-uri'] = $data['return-uri'];
        }

        $reconstructedPost = array(
            'skautIS_Token' => $_COOKIE['skautis_token'],
            'skautIS_IDRole' => '',
            'skautIS_IDUnit' => '',
            'skautIS_DateLogout' => \DateTime::createFromFormat('U', strval(time() + 60))
                ->setTimezone(new \DateTimeZone('Europe/Prague'))->format('j. n. Y H:i:s'));
        $skautis->setLoginData($reconstructedPost);
        header('Location: ' . $skautis->getLogoutUrl());
        die();
    }

    // Back from SkautIS, finish logging out and redirect back
    setcookie('skautis_token', "", time() - 3600, "/", $CONFIG->cookieuri, true, true);
    setcookie('skautis_timeout', "", time() - 3600, "/", $CONFIG->cookieuri, true, true);
    unset($_COOKIE['skautis_token']);
    unset($_COOKIE['skautis_timeout']);

    if (isset($_COOKIE['return-uri'])) {
        $redirect = $_COOKIE['return-uri'];
        setcookie('return-uri', "", time() - 3600, "/", $CONFIG->cookieuri, true, true);
        unset($_COOKIE['return-uri']);
        header('Location: ' . $redirect);
        die();
    }
    header('Location: ' . $CONFIG->baseuri);
    die();
};
$logoutEndpoint->setListMethod(new HandbookAPI\Role('guest'), $logoutUser);
$logoutEndpoint->setAddMethod(new HandbookAPI\Role('guest'), $logoutUser);
