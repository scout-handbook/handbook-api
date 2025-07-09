<?php

declare(strict_types=1);

namespace Tests;

class LegacyEndpointTestCase extends TestCase
{
    /*
     * @param  \Illuminate\Support\Uri|string  $uri
     * @param  array  $headers
     * @param  string|null $overrideRole
     * @return \Illuminate\Testing\TestResponse
     */
    public function get($uri, array $headers = [], $overrideRole = null)
    {
        global $_TEST_OVERRIDE;
        if ($overrideRole !== null) {
            $_TEST_OVERRIDE = $overrideRole;
            $_COOKIE['skautis_token'] = 'TOKEN';
            $_COOKIE['skautis_timeout'] =
                (new \DateTime('now', new \DateTimeZone('UTC')))
                    ->add(new \DateInterval('P10M'))
                    ->format('U');
        }
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $response = parent::get($uri, $headers);

        unset($_TEST_OVERRIDE);
        $_COOKIE = [];
        $_GET = [];
        unset($_SERVER['REQUEST_METHOD']);

        return $response;
    }

    /*
     * @param  \Illuminate\Support\Uri|string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @param  string|null $overrideRole
     * @return \Illuminate\Testing\TestResponse
     */
    public function post($uri, array $data = [], array $headers = [], $overrideRole = null)
    {
        global $_TEST_OVERRIDE;
        if ($overrideRole !== null) {
            $_TEST_OVERRIDE = $overrideRole;
            $_COOKIE['skautis_token'] = 'TOKEN';
            $_COOKIE['skautis_timeout'] =
                (new \DateTime('now', new \DateTimeZone('UTC')))
                    ->add(new \DateInterval('PT10M'))
                    ->format('U');
        }
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = $data;

        $response = parent::post($uri, $data, $headers);

        unset($_TEST_OVERRIDE);
        $_COOKIE = [];
        $_GET = [];
        $_POST = [];
        unset($_SERVER['REQUEST_METHOD']);

        return $response;
    }

    /*
     * @param  \Illuminate\Support\Uri|string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @param  string|null $overrideRole
     * @return \Illuminate\Testing\TestResponse
     */
    public function put($uri, array $data = [], array $headers = [], $overrideRole = null)
    {
        global $_TEST_OVERRIDE;
        if ($overrideRole !== null) {
            $_TEST_OVERRIDE = $overrideRole;
            $_COOKIE['skautis_token'] = 'TOKEN';
            $_COOKIE['skautis_timeout'] =
                (new \DateTime('now', new \DateTimeZone('UTC')))
                    ->add(new \DateInterval('P10M'))
                    ->format('U');
        }
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $_POST = $data;

        $response = parent::put($uri, $data, $headers);

        unset($_TEST_OVERRIDE);
        $_COOKIE = [];
        $_GET = [];
        $_POST = [];
        unset($_SERVER['REQUEST_METHOD']);

        return $response;
    }

    /*
     * @param  \Illuminate\Support\Uri|string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @param  string|null $overrideRole
     * @return \Illuminate\Testing\TestResponse
     */
    public function delete($uri, array $data = [], array $headers = [], $overrideRole = null)
    {
        global $_TEST_OVERRIDE;
        if ($overrideRole !== null) {
            $_TEST_OVERRIDE = $overrideRole;
            $_COOKIE['skautis_token'] = 'TOKEN';
            $_COOKIE['skautis_timeout'] =
                (new \DateTime('now', new \DateTimeZone('UTC')))
                    ->add(new \DateInterval('P10M'))
                    ->format('U');
        }
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $_POST = $data;

        $response = parent::delete($uri, $data, $headers);

        unset($_TEST_OVERRIDE);
        $_COOKIE = [];
        $_GET = [];
        $_POST = [];
        unset($_SERVER['REQUEST_METHOD']);

        return $response;
    }
}
