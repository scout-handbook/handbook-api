<?php

declare(strict_types=1);

@$_API_SECRETS_EXEC === 1 or exit('Restricted access.');

return (object) [
    'db_dsn' => 'sqlite:tests/db.sqlite',
    'db_user' => 'username',
    'db_password' => 'password',
    'skautis_app_id' => '00000000-0000-0000-0000-000000000000',
    'skautis_test_mode' => false,
];
