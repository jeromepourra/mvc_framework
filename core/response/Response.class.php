<?php

namespace core\response;

class Response
{

	/**
	 * Renvois un code & un message puis die le processus
	 */
	public static function SendCode(array $eCode)
	{
		if (isset($eCode['code']) && isset($eCode['message'])) {
			header($_SERVER['SERVER_PROTOCOL'] . " " . $eCode['code'] . " " . $eCode['message']);
			die;
		} else {
			LOGERROR("bad parameter");
		}
	}

}