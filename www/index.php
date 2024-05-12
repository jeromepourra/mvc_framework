<?php
use core\controller\Controller;

class IndexController extends Controller {

	public function run(): void {
		var_dump("Hello from run method");
	}

	public function render(string $template): void {
	}

}