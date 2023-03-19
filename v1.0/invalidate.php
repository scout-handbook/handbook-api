<?php

declare(strict_types=1);

const _API_EXEC = 1;

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');

setcookie('skautis_token', "", time() - 3600, "/", $CONFIG->cookieuri, true, true);
setcookie('skautis_timeout', "", time() - 3600, "/", $CONFIG->cookieuri, true, true);
unset($_COOKIE['skautis_token']);
unset($_COOKIE['skautis_timeout']);

header('Location: ' . $CONFIG->baseuri);
die();
