<?php

namespace core\response;
use core\response\enum\EResponseCode;

class Response implements IResponse
{
	private array $headers = [];
	private string $content;
	private EResponseCode $statusCode;

	public function __construct(string $sContent, EResponseCode $eCode = EResponseCode::OK)
	{
		$this->setContent($sContent);
		$this->setStatusCode($eCode);
	}

	public function setContent(string $sContent): self
	{
		$this->content = $sContent;
		return $this;
	}

	public function getContent(): string
	{
		return $this->content;
	}

	public function setStatusCode(EResponseCode $eCode): self
	{
		$this->statusCode = $eCode;
		return $this;
	}

	public function getStatusCode(): int
	{
		return $this->statusCode->value;
	}

	public function addHeader(string $sName, string $sValue): self
	{
		$this->headers[$sName] = $sValue;
		return $this;
	}

	public function removeHeader(string $sName): self
	{
		unset($this->headers[$sName]);
		return $this;
	}

	public function setHeaders(array $aHeaders): self
	{
		$this->headers = array_merge($this->headers, $aHeaders);
		return $this;
	}

	public function getHeaders(): array
	{
		return $this->headers;
	}

	public function getHeader(string $sName): ?string
	{
		return $this->headers[$sName] ?? null;
	}

	public function send(): self
	{
		http_response_code($this->statusCode->value);
		foreach ($this->headers as $sName => $sValue) {
			header("$sName: $sValue");
		}
		echo $this->content;
		return $this;
	}

}