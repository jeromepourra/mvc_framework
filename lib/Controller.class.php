<?php

namespace lib;

class Controller
{

	private static ?Controller $instance = null;

	private string $method;
	private array $GET;
	private array $POST;

	public static function Instance()
	{
		if (self::$instance === null) {
			self::$instance = new Controller();
		}
		return self::$instance;
	}

	private function __construct()
	{
		$this->GET = &$_GET;
		$this->POST = &$_POST;
		$this->method = $this->getMethod();
	}

	public function isMethodGET(): bool
	{
		return $this->method === "GET";
	}

	public function isMethodPOST(): bool
	{
		return $this->method === "POST";
	}

	public function getGET(string $key): mixed
	{
		return $this->getQueryItem($this->GET, $key);
	}

	public function unsetGET(string $key): bool
	{
		return $this->unsetQueryItem($this->GET, $key);
	}

	public function getPOST(string $key): mixed
	{
		return $this->getQueryItem($this->POST, $key);
	}

	public function unsetPOST(string $key): bool
	{
		return $this->unsetQueryItem($this->POST, $key);
	}

	public function isConnected(): bool
	{
		$user = Session()->get('user');
		if ($user) {
			return true;
		} else {
			return false;
		}
	}

	private function getMethod(): string
	{
		if (isset($_SERVER['REQUEST_METHOD'])) {
			$method = $_SERVER['REQUEST_METHOD'];
			return strtoupper($method);
		}
		return "GET";
	}

	/**
	 * @param array $method La méthode HTTP
	 * @param string $key La clef sur laquelle est stocké la valeur a récupérer
	 */
	private function getQueryItem(array &$method, string $key): mixed {
		if (array_key_exists($key, $method)) {
			return $this->GET[$key];
		} else {
			return null;
		}
	}

	/**
	 * @param array $method La méthode HTTP
	 * @param string $key La clef sur laquelle est stocké la valeur a récupérer
	 */
	private function unsetQueryItem(array &$method, string $key): bool {
		if (array_key_exists($key, $method)) {
			unset($method, $key);
			return true;
		} else {
			return false;
		}
	}

}