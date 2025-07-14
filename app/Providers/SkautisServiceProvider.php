<?php

declare(strict_types=1);

namespace App\Providers;

use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Skaut\HandbookAPI\v1_0\Exception\AuthenticationException;
use Skautis\Skautis;

final class SkautisServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        /** @throws AuthenticationException */
        $this->app->singleton(Skautis::class, static function () {
            $skautis = Skautis::getInstance(Config::get('skautis.app_id'), Config::get('skautis.test_mode'));

            if (! isset($_COOKIE['skautis_token']) or ! isset($_COOKIE['skautis_timeout'])) {
                throw new AuthenticationException;
            }

            $dateLogout = DateTime::createFromFormat('U', $_COOKIE['skautis_timeout']);

            if ($dateLogout === false) {
                $dateLogout = (new DateTime('now', new DateTimeZone('UTC')))->add(new DateInterval('PT10M'));
            }

            $dateLogout = $dateLogout->setTimezone(new DateTimeZone('Europe/Prague'))->format('j. n. Y H:i:s');
            $reconstructedPost = [
                'skautIS_DateLogout' => $dateLogout,
                'skautIS_Token' => $_COOKIE['skautis_token'],
            ];
            $skautis->setLoginData($reconstructedPost);

            return $skautis;
        });
    }
}
