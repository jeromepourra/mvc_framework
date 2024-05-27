<?php

namespace lib;

use Exception;

/*
 **************************************************
 ** Config [singleton]
 **************************************************
 **
 ** class de configuration globale de l'application
 ** instancié au bootstrap et accessible partout
 **
 **************************************************
 */

class Config
{

	private static ?Config $instance = null;
	
	/**
	 * @var array<string, mixed> $config
	 */
	private array $config;

	public static function Instance(): Config
	{
		if (self::$instance === null) {
			self::$instance = new Config();
		}
		return self::$instance;
	}

	private function __construct()
	{
		$this->config = [];
		$this->config['error_reporting'] = E_ALL;
		$this->config['error_log'] = App()->mkPath("logs/php.error.log");
		$this->config['session.save_path'] = App()->mkPath("tmp");
		$this->config['xdebug.var_display_max_depth'] = -1;
		$this->config['xdebug.var_display_max_children'] = -1;
		$this->config['xdebug.var_display_max_data'] = -1;
	}

	/**
	 * @return array<string, mixed>
	 */
	public function getAll(): array
	{
		return $this->config;
	}

	public function get(string $key): mixed
	{
		if (array_key_exists($key, $this->config)) {
			return $this->config[$key];
		} else {
			return null;
		}
	}

	public function set(string $key, mixed $value): void
	{

		if (is_null($value) && $this->keyExists($key)) {
			// si la valeur est défini à null
			// et que la clef existe on supprime la clef
			unset($this->config[$key]);
		} else {
			$this->config[$key] = $value;
		}
	}

	public function loadEnvFile(string $path): void
	{
		if (file_exists($path)) {
			$data = parse_ini_file($path, true, INI_SCANNER_TYPED);
			if (is_array($data)) {
				foreach ($data as $key => $val) {
					$this->set($key, $val);
				}
			}
		} else {
			throw new Exception("Config->loadEnvFile() : can't open file $path");
		}
	}

	private function keyExists(string $key): bool
	{
		return array_key_exists($key, $this->config);
	}

}