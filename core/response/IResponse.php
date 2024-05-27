<?php

namespace core\response;

interface IResponse
{
	public function send(): self;
}