<?php

namespace src\controller;

use core\router\Route;
use src\model\UserModel;

class HomeController
{

	#[Route(path: "/", methods: ["GET"])]
	public function index()
	{
		echo "<h1>Welcome home !</h1>";
		$oUser = new UserModel();
		$oEntity = $oUser->findOneById(1);
		var_dump($oEntity);
	}

	#[Route(path: "/hello/{name}", methods: ["GET"], patterns: ['name' => "[A-Za-z-]+"])]
	public function hello(string $name)
	{
		echo "Hello " . $name;
	}

	#[Route(path: "/hello/{firstname}/{lastname}", methods: ["GET"], patterns: ['firstname' => "[A-Za-z-]+", 'lastname' => "[A-Za-z-]+"])]
	public function helloFullname(string $firstname, string $lastname)
	{
		echo "Hello " . $firstname . " : " . $lastname;
	}
}