<?php

namespace src\entity;

use core\database\Entity;

class UserEntity extends Entity
{
	public ?int $id = null;
	public ?string $name = null;
	public ?string $password = null;
}