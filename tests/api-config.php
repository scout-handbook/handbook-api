<?php

declare(strict_types=1);

@_API_EXEC === 1 or exit('Restricted access.');

$CONFIG = (object) [
    'apiuri' => 'https://odymaterialy.test/API',
    'basepath' => $_SERVER['DOCUMENT_ROOT'].'/../legacy',
    'baseuri' => 'https://odymaterialy.test',
    'cookieuri' => 'odymaterialy.test',
    'imagepath' => $_SERVER['DOCUMENT_ROOT'].'/images',
];
