<?php

namespace lib;

interface IController
{
	public function render(string $template): void;
}