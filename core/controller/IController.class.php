<?php

namespace core\controller;

interface IController
{
	public function run(): void;
	public function render(string $template): void;
}