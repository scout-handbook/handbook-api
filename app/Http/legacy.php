<?php

$url = \Request::path();
if (substr($url, 0, 4) === "API/" ) {
    $url = substr($url, 4);
}
if ($url === "login") {
    $url = "v1.0/login";
}
if ($url === "logout") {
    $url = "v1.0/logout";
}
$parts = explode( '/', $url );
$url_base = implode("/", array_slice($parts, 0, 2));
require __DIR__ . '/../../legacy/' . $url_base . '.php';
