<?php

namespace lib;

use Throwable;

class Logger
{

	// Nom des erreurs à afficher dans les logs
	private const ERROR_NAMES = [
		E_ERROR => "FATAL",
		E_WARNING => "WARNING",
		E_NOTICE => "DEBUG",
		E_DEPRECATED => "DEPRECATED",
		E_USER_ERROR => "FATAL",
		E_USER_WARNING => "WARNING",
		E_USER_NOTICE => "DEBUG",
		E_USER_DEPRECATED => "DEPRECATED",
		"UNKNOWN" => "UNKNOWN"
	];

	private static ?array $TraceSnapshot;
	private static ?array $ContextSnapshot;

	public static function Notice(string $message, mixed ...$args)
	{
		self::Trigger(E_USER_NOTICE, $message, ...$args);
	}

	public static function Warning(string $message, mixed ...$args)
	{
		self::Trigger(E_USER_WARNING, $message, ...$args);
	}

	public static function Error(string $message, mixed ...$args)
	{
		self::Trigger(E_USER_ERROR, $message, ...$args);
	}

	public static function Deprecated(string $message, mixed ...$args)
	{
		self::Trigger(E_USER_DEPRECATED, $message, ...$args);
	}

	public static function BacktraceSnapshot(int $offset = 0, ?int $length = null)
	{

		/**
		 * 	Je préfère passer par une variable de class pour faire une snapshot de la trace
		 * 	Je pourrais envoyer la trace au handler d'erreur avec un encode json du message
		 * 
		 * 	$message = [
		 * 		'trace' => debug_backtrace(),
		 * 		'message' => "Ceci est un message de log"
		 * 	];
		 * 	trigger_error($message, E_LEVEL);
		 * 
		 * 	Evidemment cette variable de class ne doit pas être réécrite entre ici et sa lecture dans
		 * 	sa lecture dans la méthode self::LogFile();
		 * 
		 * 	Pour éviter la réécriture on pourrait stocker la snapshot dans un tableau qui contiendrait
		 * 	l'id du log, et passer ça dans le message. Puis aller récupérer la snapshot dans la méthod
		 * 	self::LogFile(); A méditer...
		 */

		self::$TraceSnapshot = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);
		/**
		 * Supprime de la trace les appels de Wrapper et Logger
		 * On ne garde que ce qui s'est passé à l'exterieur du logger :)
		 */
		array_splice(self::$TraceSnapshot, $offset, $length);

		if (count(self::$TraceSnapshot) > 0) {
			// Snapshot de l'appel qui a mené à l'erreur
			self::$ContextSnapshot = self::$TraceSnapshot[0];
		} else {
			self::$ContextSnapshot = null;
		}
	}

	public static function ListenEvents()
	{
		self::ListenShutdownEvent();
		self::ListenErrorEvent();
		self::ListenExceptionEvent();
	}

	private static function ListenShutdownEvent()
	{
		$handler = function () {
			$error = error_get_last();
			$level = isset ($error['type']) ? $error['type'] : null;
			$message = isset ($error['message']) ? $error['message'] : null;
			$file = isset ($error['file']) ? $error['file'] : null;
			$line = isset ($error['line']) ? $error['line'] : null;
			if ($level === E_ERROR) {
				self::LogFile($level, $message, $file, $line, null);
			}
		};
		register_shutdown_function($handler);
	}

	private static function ListenErrorEvent()
	{
		/**
		 * @var callable $handler
		 */
		$handler = function (int $level = null, string $message = null, string $file = null, int $line = null, array $context = null): void {
			self::LogFile($level, $message, $file, $line, $context);
		};
		set_error_handler($handler, Config()->get('error_reporting'));
	}

	private static function ListenExceptionEvent()
	{
		$handler = function (Throwable $e = null) {
			self::LogFile(E_ERROR, $e->getMessage(), $e->getFile(), $e->getLine());
		};
		set_exception_handler($handler);
	}

	private static function Trigger(int $level, string $message, mixed ...$args)
	{
		// Voir fichier tools/phpunit.xml
		if (!defined("__PHPUNIT_RUNNING__")) {
			// Ramasse les erreurs depuis l'application
			self::BacktraceSnapshot(0, 3);
			trigger_error(sprintf($message, ...$args), $level);
		}
		// PHP Unit est lancé, laisse passer les erreurs
	}

	private static function IsFatal(int $level): bool
	{
		if ($level & E_ERROR || $level & E_USER_ERROR) {
			return true;
		} else {
			return false;
		}
	}

	private static function LogFile(int $level = null, string $message = null, string $file = null, int $line = null, array $context = null)
	{

		$backtrace = false;
		if ($level & E_ERROR || $level & E_USER_ERROR) {
			$backtrace = true;
		}

		$microtime = microtime(true);
		$microsecond = $microtime - floor($microtime);
		$millisecond = round($microsecond, 3) * 1000;
		$sMillisecond = str_pad($millisecond, 3, "0", STR_PAD_RIGHT);
		$now = date("H:i:s") . "." . $sMillisecond;

		$errTitle = self::ERROR_NAMES[$level];
		if (is_null($errTitle)) {
			$errTitle = self::ERROR_NAMES["UNKNOWN"];
		}

		$log = "";
		$log .= ">-+---------------------+" . PHP_EOL;
		$log .= "  | " . $now . " [" . $errTitle . "] " . self::SnapshotGetFile($file, self::$ContextSnapshot) . "(" . self::SnapshotGetLine($line, self::$ContextSnapshot) . ") " . self::SnapshotGetCaller(self::$ContextSnapshot) . PHP_EOL;
		foreach (explode("\n", $message) as $messageLine) {
			$log .= "  | " . $messageLine . PHP_EOL;
		}
		if ($backtrace) {
			$log .= self::SnapshotGetTrace();
		}

		$logFile = Config()->get('error_log');
		$logDir = dirname($logFile);

		if (!file_exists($logDir)) {
			mkdir($logDir, recursive: true);
		}

		file_put_contents($logFile, $log, FILE_APPEND);

		self::$TraceSnapshot = null;
		self::$ContextSnapshot = null;

		if (self::IsFatal($level)) {
			die;
		}

	}

	private static function SnapshotGetFile(?string $file, mixed $snapshot): string
	{
		if (isset($snapshot['file'])) {
			$file = $snapshot['file'];
		}
		$file = str_replace("\\", "/", $file);
		$file = str_replace(App()->mkPath(), "", $file);
		if (str_starts_with($file, "/")) {
			$file = substr($file, 1);
		}
		return $file;
	}

	private static function SnapshotGetLine(?int $line, mixed $snapshot): int
	{
		if (isset($snapshot['line'])) {
			return $snapshot['line'];
		}
		return $line;
	}

	private static function SnapshotGetCaller(mixed $snapshot): ?string
	{
		$class = isset($snapshot['class']) ? $snapshot['class'] : "";
		$type = isset($snapshot['type']) ? $snapshot['type'] : "";
		$function = isset($snapshot['function']) ? $snapshot['function'] : "";
		if (!empty($class)) {
			return "-> " . $class . $type . $function . "()";
		} else if (!empty($function)) {
			return "-> " . $function . "()";
		}
		return null;
	}

	private static function SnapshotGetTrace()
	{
		if (is_array(self::$TraceSnapshot) && count(self::$TraceSnapshot) > 0) {
			$message = "";
			$message .= "  |" . PHP_EOL;
			$message .= "  | Stack trace:" . PHP_EOL;
			foreach (self::$TraceSnapshot as $i => $trace) {
				/**
				 * Make
				 * \_ Level 1
				 *   \_ Level 2
				 *     \_ Level 3
				 *       \_ ...
				 */
				if ($i === 0) {
					$start = "  ";
				} else {
					$start = "  " . str_repeat("| ", $i);
				}
				$message .= $start . "\_ " . self::SnapshotGetFile(null, $trace) . "(" . self::SnapshotGetLine(null, $trace) . ") " . self::SnapshotGetCaller($trace) . PHP_EOL;
			}
			return $message;
		} else {
			return null;
		}
	}

}

// Ecoute les logs events
Logger::ListenEvents();