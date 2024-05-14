<?php

namespace core\router;

/**
 * 	Données du chunk d'un path
 * 		- path: /hello/{name}
 * 		- chunks: [/, hello, {name}]
 */
class Chunkpath {

	public string $chunk;
	public int $index; 
	public bool $variable;
	public ?string $name;
	public ?string $pattern;

	private string $value;

	public function __construct(string $chunk, int $index, bool $variable = false, ?string $name = null, ?string $pattern = null) {
		$this->chunk = $chunk;
		$this->index = $index;
		$this->variable = $variable;
		$this->name = $name;
		$this->pattern = $pattern;
	}

	public function getValue(): string {
		return $this->value;
	}

	public function setValue(string $value): void {
		$this->value = $value;
	}

	public function isMatch(string $sChunk): bool
	{
		return $this->chunk === $sChunk;
	}

	public function isMatchPattern(string $sChunk): bool
	{
		return preg_match($this->pattern, $sChunk);
	}

	public function isMatchName(string $sName): bool
	{
		return $this->name === $sName;
	}

}