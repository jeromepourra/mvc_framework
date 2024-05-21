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
require __BOOTSTRAP_DIR__ . "/lib/Singleton.class.php"; // FIX ME
require __BOOTSTRAP_DIR__ . "/lib/App.class.php";

// Initialisation des chemin racine (relatifs) de l'app
App()->buildRootPath(__BOOTSTRAP_DIR__);
App()->buildRootUrl();

require App()->mkPath("lib/functions.php");
require App()->mkPath("lib/autoload.php");

// Charge les variables d'environnement dans la config
Config()->loadEnvFile(App()->mkPath(".env"));

// Charge la configuration de PHP
// require App()->mkPath("ini.php");

BufferOn();