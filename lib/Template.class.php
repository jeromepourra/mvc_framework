<?php

namespace lib;

class Template
{

	private array $vars = [];
	private ?string $document = null;

	public function assign(string $key, mixed $val = null): void
	{
		if (is_null($val) && array_key_exists($key, $this->vars)) {
			unset($this->vars[$key]);
		} else {
			$this->vars[$key] = $val;
		}
	}

	public function assignArray(array $associative): void
	{
		$this->vars = array_merge($this->vars, $associative);
	}

	public function load(string $path): void
	{

		$path = App()->mkTemplatePath($path);

		if (file_exists($path)) {
			$this->document = $path;
		} else {
			LOGERROR("can't load template %s", $path);
		}

	}

	public function dump(): void
	{
		if (is_null($this->document)) {
			LOGERROR("no template file loaded");
		}
		if (!empty($this->vars)) {
			extract($this->vars, EXTR_OVERWRITE);
		}
		require $this->document;
	}

}