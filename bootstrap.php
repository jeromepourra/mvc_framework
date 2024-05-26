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

// Normalisation du path "\" -> "/"
define("__BOOTSTRAP_DIR__", str_replace(DIRECTORY_SEPARATOR, "/", __DIR__));

require __BOOTSTRAP_DIR__ . "/lib/wrapper.php";
require __BOOTSTRAP_DIR__ . "/lib/Singleton.php"; // FIX ME
require __BOOTSTRAP_DIR__ . "/lib/App.php";

// Initialisation des chemin racine (relatifs) de l'app
App()->buildRootPath(__BOOTSTRAP_DIR__);
App()->buildRootUrl();

require App()->mkPath("lib/functions.php");
require App()->mkPath("lib/autoload.php");

// Charge les variables d'environnement dans la config
if (defined("__PHPUNIT_RUNNING__") && __PHPUNIT_RUNNING__ === true) {
	Config()->loadEnvFile(App()->mkPath("_test.env"));
} else {
	Config()->loadEnvFile(App()->mkPath("_main.env"));
}

// Charge la configuration de PHP
require App()->mkPath("ini.php");

BufferOn();