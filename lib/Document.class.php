<?php

namespace lib;

class Document
{

	private static ?Document $instance = null;

	private string $body = "";
	private array $jsFiles = [];
	private array $cssFiles = [];
	private array $pageMessages = [];

	public static function Instance()
	{
		if (self::$instance === null) {
			self::$instance = new Document();
		}
		return self::$instance;
	}

	private function __construct()
	{
	}

	public function setBody(string $body): self
	{
		$this->body = $body;
		return $this;
	}

	public function getBody(): string
	{
		return $this->body;
	}

	public function getJsFiles(): array
	{
		return $this->jsFiles;
	}

	public function getCssFiles(): array
	{
		return $this->cssFiles;
	}

	public function addJsFile(string $path, bool $isModule = false)
	{
		$fullPath = App()->mkUrl($path);
		array_push($this->jsFiles, [
			'path' => $fullPath,
			'module' => $isModule
		]);
	}

	public function addCssFile(string $path)
	{
		$fullPath = App()->mkUrl($path);
		array_push($this->cssFiles, $fullPath);
	}

	public function addPageSuccessMessage(string $message, mixed ...$args): void
	{
		$this->addPageMessage("success", $message, ...$args);
	}

	public function addPageWarningMessage(string $message, mixed ...$args): void
	{
		$this->addPageMessage("warning", $message, ...$args);
	}

	public function addPageErrorMessage(string $message, mixed ...$args): void
	{
		$this->addPageMessage("error", $message, ...$args);
	}

	public function getPageMessages(): array
	{
		return $this->pageMessages;
	}

	public function addPageMessage(string $type, string $message, mixed ...$args): void
	{
		if (isset($this->pageMessages[$type]) && is_array($this->pageMessages[$type])) {
			// pousse le message dans le tableau de type existant
			array_push($this->pageMessages[$type], sprintf($message, ...$args));
		} else {
			// initialize le tableau de type avec le message
			$this->pageMessages[$type] = [sprintf($message, ...$args)];
		}
	}

	public function addNextPageSuccessMessage(string $message): void
	{
	}

	public function addNextPageWarningMessage(string $message): void
	{
	}

	public function addNextPageErrorMessage(string $message): void
	{
	}

	public function addNextPageMessage()
	{

	}


}