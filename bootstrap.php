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

// Chemin racine de l'application (ce dossier)
define("__BOOTSTRAP_DIR__", str_replace(DIRECTORY_SEPARATOR, "/", __DIR__));

// Chargement de l'autoloader en premier lieu
require __BOOTSTRAP_DIR__ . "/lib/autoload.php";

// Chargement du wrapper et des fonctions
require __BOOTSTRAP_DIR__ . "/lib/wrapper.php";
require __BOOTSTRAP_DIR__ . "/lib/functions.php";

// Initialisation des chemin racine (relatifs) de l'app
App()->buildRootPath(__BOOTSTRAP_DIR__);
App()->buildRootUrl();

// Charge les variables d'environnement dans la config
if (defined("__PHPUNIT_RUNNING__") && __PHPUNIT_RUNNING__ === true) {
	Config()->loadEnvFile(App()->mkPath("_test.env"));
} else {
	Config()->loadEnvFile(App()->mkPath("_main.env"));
}

// Charge la configuration de PHP
require App()->mkPath("ini.php");

// Active le buffer de sortie
BufferOn();