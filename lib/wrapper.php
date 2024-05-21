<?php

/*
 **************************************************
 ** wrapper 
 **************************************************
 **
 ** permet d'éviter la verbosité des méthodes de class
 ** englobe des méthodes chiante à écrire dans des
 ** fonctions simples et courte.
 **
 ** ce fichier rend les choses accessible d'une manière
 ** simplifié
 **
 ** MyClass::Instance() >>> MyClass()
 **
 **************************************************
 */

use lib\App;
use lib\Logger;
use lib\Config;
use lib\Session;
use lib\Document;

use core\router\Router;
use core\database\Database;

function App(): App
{
	return App::Instance();
}

function Config(): Config
{
	return Config::Instance();
}

function Session(): Session
{
	return Session::Instance();
}

function Document(): Document
{
	return Document::Instance();
}

function Router(): Router
{
	return Router::Instance();
}

function Database(): Database
{
	return Database::Instance();
}

function LOGDEBUG(string $message, mixed ...$args): void
{
	Logger::Notice($message, ...$args);
}

function LOGWARNING(string $message, mixed ...$args): void
{
	Logger::Warning($message, ...$args);
}

function LOGERROR(string $message, mixed ...$args): void
{
	Logger::Error($message, ...$args);
}

function LOGDEPRECATED(string $message, mixed ...$args): void
{
	Logger::Deprecated($message, ...$args);
}