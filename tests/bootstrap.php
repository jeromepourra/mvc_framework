<?php

const __PHPUNIT_RUNNING__ = true;

$_SERVER['REQUEST_URI'] = "/";
$_SERVER['REQUEST_METHOD'] = "GET";
$_SERVER['SERVER_PROTOCOL'] = "HTTP/1.1";

// Charge le fichier de démarage de l'application
require __DIR__ . "/../bootstrap.php";