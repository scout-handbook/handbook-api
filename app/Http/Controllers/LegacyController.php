<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class LegacyController extends Controller
{
    public function __invoke()
    {
        ob_start();
        require app_path('Http') . '/legacy.php';
        $output = ob_get_clean();
     
        return new Response($output);
    }
}
