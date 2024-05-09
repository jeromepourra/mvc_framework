<?php

namespace lib;

/**
 **************************************************
 * Session [singleton]
 **************************************************
 *
 * Wrapper pour la variable $_SESSION accessible depuis la fonction Session().
 * 
 * ```md
 * 	#avbc
 * ```
 * 
 * <code>
 * <?php
 * Session()->get("key");
 * Session()->get("key", "subKey", "subSubKey", "...");
 * Session()->set("key", "value");
 * ?>
 * </code>
 * 
 * Des variables temporaires peuvent être définis et peuvent être utilisées par exemple pour afficher  des données sur la prochaine page chargé par l'application
 * Les données de session temporaires sont automatiquement détruite après lecture
 *
 **************************************************
 */
class Session
{

	private static ?Session $instance = null;

	private const TEMP_KEY = "__temp__";
	private array $session;
	private array $temp;

	public static function Instance()
	{
		if (self::$instance === null) {
			self::$instance = new Session();
		}
		return self::$instance;
	}

	private function __construct()
	{

		// démarre la session
		$this->start();

		// référence vers la variable $_SESSION
		// si on modifie l'un on modifie l'autre
		$this->session = &$_SESSION;

		if (!$this->initialized()) {
			// initialisation de la session
			$this->initialize();
		}

		// assign les variables temporaires dans une prop dédiée
		$this->temp = &$this->session[self::TEMP_KEY];

	}

	private function initialized(): bool
	{
		return isset($this->session['initialized']);
	}

	private function initialize(): void
	{
		$this->session['initialized'] = true;
		$this->session[self::TEMP_KEY] = []; // pas touche !
	}

	public function start(): void
	{
		session_start();
	}

	public function close(): void
	{
		session_write_close();
	}

	public function reset(): void
	{
		session_unset();
		$this->initialize();
	}

	public function getAll(): array
	{
		return $this->session;
	}

	public function get(string $key): mixed
	{
		if (array_key_exists($key, $this->session)) {
			return $this->session[$key];
		} else {
			return null;
		}
	}

	public function set(string $key, mixed $value): void
	{
		if (is_null($value) && array_key_exists($key, $this->session)) {
			// si la valeur est défini à null et que la clef existe on supprime la clef
			$this->unsetKey($key);
		} else {
			$this->session[$key] = $value;
		}
	}

	//
	// variables de session temporaires
	// ================================

	public function setTemp(string $key, mixed $value): void
	{
		if (is_null($value) && array_key_exists($key, $this->temp)) {
			// si la valeur est défini à null et que la clef existe on supprime la clef
			$this->unsetKey($key);
		} else {
			$this->temp[$key] = $value;
		}
	}

	public function getTemp(string $key): mixed
	{
		if (array_key_exists($key, $this->temp)) {
			$value = $this->temp[$key];
			$this->unsetKey($key);
			return $value;
		} else {
			return null;
		}
	}

	private function unsetKey(string $key): void
	{
		unset($this->temp[$key]);
	}

}