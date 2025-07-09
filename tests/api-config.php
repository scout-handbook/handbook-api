<?php

declare(strict_types=1);

@_API_EXEC === 1 or exit('Restricted access.');

$CONFIG = (object) [
    'basepath' => $_SERVER['DOCUMENT_ROOT'].'/../legacy',
    'imagepath' => $_SERVER['DOCUMENT_ROOT'].'/images',
    'cookieuri' => 'odymaterialy.test',
    'baseuri' => 'https://odymaterialy.test',
    'apiuri' => 'https://odymaterialy.test/API',
];
