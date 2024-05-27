<?php

namespace core\response\enum;

enum EResponseType: string
{
	case HTML = "text/html";
	case JSON = "application/json";
}