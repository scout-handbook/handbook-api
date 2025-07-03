<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Config;
use Skautis\Skautis;

class LoginController extends Controller
{
    public function authenticate(Request $request): RedirectResponse
    {
        $startsWith = function (string $haystack, string $needle): bool {
            return (mb_substr($haystack, 0, mb_strlen($needle)) === $needle);
        };
        $ISprefix = Config::get('skautis.test_mode') ? 'https://test-is.skaut.cz' : 'https://is.skaut.cz';

        // Back from SkautIS, finish logging in and redirect back
        if (isset($_SERVER['HTTP_REFERER']) and $startsWith($_SERVER['HTTP_REFERER'], $ISprefix)) {
            $token = $request->input('skautIS_Token');
            $timeout = \DateTime::createFromFormat(
                'j. n. Y H:i:s',
                $request->input('skautIS_DateLogout'),
                new \DateTimeZone('Europe/Prague')
            );
            if (!$timeout) {
                $timeout = (new \DateTime('now', new \DateTimeZone('Europe/Prague')))->add(new \DateInterval('PT10M'));
            }
            $timeout = $timeout->format('U');

            setcookie('skautis_token', $token, intval($timeout), "/", Config::get('uris.cookie_uri'), true, true);
            setcookie('skautis_timeout', $timeout, intval($timeout), "/", Config::get('uris.cookie_uri'), true, false);
            $_COOKIE['skautis_token'] = $token;
            $_COOKIE['skautis_timeout'] = $timeout;

            // TODO
            //$accountEndpoint->call('POST', new \Role('user'), []);

            $redirect = Config::get('uris.base_uri');
            if (isset($_COOKIE['return-uri'])) {
                $redirect = $_COOKIE['return-uri'];
                setcookie('return-uri', "", time() - 3600, "/", Config::get('uris.cookie_uri'), true, true);
                unset($_COOKIE['return-uri']);
            }
            if ($startsWith($redirect, 'http://')) {
                $redirect = 'https://' . mb_substr($redirect, 7);
            } elseif (!$startsWith($redirect, 'https://')) {
                if (!$startsWith($redirect, '/')) {
                    $redirect = '/' . $redirect;
                }
                $redirect = Config::get('uris.base_uri') . $redirect;
            }
            header('Location: ' . $redirect);
            die();
        }

        // Called by user, set up and redirect to SkautIS
        if (isset($data['return-uri'])) {
            $redirect = $data['return-uri'];
        } elseif (isset($_SERVER['HTTP_REFERER']) and $startsWith($_SERVER['HTTP_REFERER'], Config::get('uris.base_uri'))) {
            $redirect = mb_substr($_SERVER['HTTP_REFERER'], mb_strlen(Config::get('uris.base_uri')));
        }
        if (isset($redirect)) {
            setcookie('return-uri', $redirect, time() + 600, "/", Config::get('uris.cookie_uri'), true, true);
            $_COOKIE['return-uri'] = $redirect;
        }
        header('Location: ' . Skautis::getInstance(Config::get('skautis.app_id'), Config::get('skautis.test_mode'))->getLoginUrl());
        die();
    }
}
