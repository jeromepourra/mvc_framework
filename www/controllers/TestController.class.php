<?php
namespace www\controllers;
use core\router\Route;

class TestController {

	#[Route(path: "/test", methods: ["GET"])]
	public function test() {
		echo "TestController::test()";
	}

}