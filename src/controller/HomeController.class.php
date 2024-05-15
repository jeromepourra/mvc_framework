<?php

namespace src\controller;
use core\router\Route;

class HomeController
{
	#[Route(path: "/hello/{name}", methods: ["GET"], patterns: ['name' => "[A-Za-z-]+"])]
	public function hello(string $name)
	{
		echo "HomeController::hello() -> " . $name;
	}

	#[Route(path: "/print/{firstname}/{lastname}", methods: ["GET"], patterns: ['firstname' => "[A-Za-z-]+", 'lastname' => "[A-Za-z-]+"])]
	public function print(string $firstname, string $lastname)
	{
		echo "HomeController::print() -> " . $firstname . " : " . $lastname;
	}
}