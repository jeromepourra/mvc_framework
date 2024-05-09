<?php
/*
**************************************************
** Bootstrap
**************************************************
**
** initialisation de l'application
** toutes les pages commence par ce fichier
**
**************************************************
*/

require __DIR__ . "/lib/wrapper.php";
require __DIR__ . "/lib/App.class.php";

App()->buildRootPath(__DIR__);
App()->buildRootUrl(__DIR__ . DIRECTORY_SEPARATOR . "www");

require App()->mkPath("lib/functions.php");
require App()->mkPath("lib/autoload.php");

// Charge les variables d'environnement dans la config
Config()->loadEnvFile(App()->mkPath(".env"));

// Charge la configuration de PHP
require App()->mkPath("lib/php_config.php");

Session();

LOGDEBUG("___BOOTSTRAP___");

BufferOn();