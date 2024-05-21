<?php

namespace src\model;

use core\database\Model;
use src\entity\UserEntity;

class UserModel extends Model
{
	protected const TABLE = "user";
	protected const ENTITY = UserEntity::class;
}