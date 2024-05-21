<?php

use src\controller\HomeController;
use src\controller\TestController;

require "./../bootstrap.php";

Router()->register(HomeController::class);
Router()->register(TestController::class);
Router()->load($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

require "./../end.php";