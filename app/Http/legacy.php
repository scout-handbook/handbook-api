<?php

$url = \Request::path();
require __DIR__ . '/../../legacy/' . $url . '.php';
