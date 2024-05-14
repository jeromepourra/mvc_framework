<?php
use www\controllers\HomeController;

require "./../bootstrap.php";

Router()->register(HomeController::class);
Router()->load($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

require "./../end.php";