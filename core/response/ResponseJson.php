<?php

namespace core\response;
use core\response\enum\EResponseCode;
use core\response\enum\EResponseType;

class ResponseJson extends Response implements IResponse
{
	public function __construct(string $sContent, EResponseCode $eCode = EResponseCode::OK)
	{
		parent::__construct($sContent, $eCode);
	}

	public function send(): self
	{
		$this->addHeader("Content-Type", EResponseType::JSON->value);
		return parent::send();
	}
}