<?php

require "./../bootstrap.php";

$sUrl = Controller()->getGET("url");
Controller()->unsetGET("url"); // plus aucune utilité une fois stocké

if ($sUrl !== null) {

	if (empty($sUrl)) {
		// si la chaine est vide -> $sUrl = "/index.php"
		$sUrl = "/index.php";
	}

	$sFilePath = App()->mkWebPath($sUrl);

	if (file_exists($sFilePath)) {
		LOGDEBUG("Load page: %s", $sFilePath);
		require_once $sFilePath;
	} else {
		LOGDEBUG("Try to load unexisting page: %s", $sFilePath);
	}

}

require "./../end.php";