<?php

namespace src\entity;

class UserEntity
{
	public ?int $id = null;
	public ?string $name = null;
	public ?string $password = null;

	public function __construct()
	{
	}
}