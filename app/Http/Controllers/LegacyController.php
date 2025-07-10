<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class LegacyController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $url = $request->path();

        if (substr($url, 0, 4) === 'API/') {
            $url = substr($url, 4);
        }

        if ($url === 'login') {
            $url = 'v1.0/login';
        }

        if ($url === 'logout') {
            $url = 'v1.0/logout';
        }

        $parts = explode('/', $url);
        $url_base = implode('/', array_slice($parts, 0, 2));

        if (count($parts) >= 3) {
            $_GET['id'] = $parts[2];
        }

        if (count($parts) >= 4) {
            $_GET['sub-resource'] = $parts[3];
        }

        if (count($parts) >= 5) {
            $_GET['sub-id'] = $parts[4];
        }

        ob_start();
        require __DIR__.'/../../../legacy/'.$url_base.'.php';
        $output = ob_get_clean();

        return new Response($output, (int) http_response_code());
    }
}
