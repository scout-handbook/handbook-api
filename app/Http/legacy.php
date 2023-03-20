<?php

$url = \Request::path();
require __DIR__ . '/../../legacy/' . substr($url, 4) . '.php';
