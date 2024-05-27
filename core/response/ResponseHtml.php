<?php

namespace core\response;
use core\response\enum\EResponseCode;
use core\response\enum\EResponseType;

class ResponseHtml extends Response implements IResponse
{
	public function __construct(string $sContent, EResponseCode $eCode = EResponseCode::OK)
	{
		parent::__construct($sContent, $eCode);
	}

	public function send(): self
	{
		$this->addHeader("Content-Type", EResponseType::HTML->value);
		return parent::send();
	}
}