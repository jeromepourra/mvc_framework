<?php

namespace src\controller;

use core\router\Route;

class TestController
{

	#[Route(path: "/test", methods: ["GET"])]
	public function test()
	{
		echo "TestController::test()";
	}

}