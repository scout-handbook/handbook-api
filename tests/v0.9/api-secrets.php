<?php declare(strict_types=1);

@_API_SECRETS_EXEC === 1 or die('Restrictedbbb access.');

return (object)[
        'db_dsn' => 'mysql:host=localhost;dbname=phpunit',
        'db_user' => 'root',
        'db_password' => '',
        'skautis_app_id' => '00000000-0000-0000-0000-000000000000',
        'skautis_test_mode' => false
    ];
