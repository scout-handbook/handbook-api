<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9;

@_API_EXEC === 1 or die('Restricted access.');

use Skautis\Skautis;

use Skaut\HandbookAPI\v0_9\Exception\AuthenticationException;
use Skaut\HandbookAPI\v0_9\Exception\SkautISException;

function skautisTry(callable $callback, bool $hardCheck = true)
{
    $_API_SECRETS_EXEC = 1;
    $SECRETS = require($_SERVER['DOCUMENT_ROOT'] . '/api-secrets.php');
    $skautis = Skautis::getInstance($SECRETS->skautis_app_id, $SECRETS->skautis_test_mode);
    if (isset($_COOKIE['skautis_token']) and isset($_COOKIE['skautis_timeout'])) {
        $reconstructedPost = array(
            'skautIS_Token' => $_COOKIE['skautis_token'],
            'skautIS_IDRole' => '',
            'skautIS_IDUnit' => '',
            'skautIS_DateLogout' => \DateTime::createFromFormat('U', $_COOKIE['skautis_timeout'])
                ->setTimezone(new \DateTimeZone('Europe/Prague'))->format('j. n. Y H:i:s'));
        $skautis->setLoginData($reconstructedPost);
        if ($skautis->getUser()->isLoggedIn($hardCheck)) {
            try {
                return $callback($skautis);
            } catch (\Skautis\Exception $e) {
                throw new SkautISException($e);
            }
        }
    }
    throw new AuthenticationException();
}
