<?php

declare(strict_types=1);

if (!defined("_API_EXEC")) {
    define("_API_EXEC", 1);
}

require($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
//require_once($CONFIG->basepath . '/vendor/autoload.php');
require($CONFIG->basepath . '/v1.0/endpoints/fieldEndpoint.php');

$fieldEndpoint->handle();
