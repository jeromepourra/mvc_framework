<?php

require "./../bootstrap.php";

if (isset($_GET['url'])) {

	$sUrl = $_GET['url'];
	unset($_GET['url']);

	if (empty($sUrl)) {
		// si la chaine est vide -> $sUrl = "/index.php"
		$sUrl = "/index.php";
	}

	$oController = Router()->loadController($sUrl);
	$oController->run();

} else {
	LOGWARNING("url is not defined on query");
}

require "./../end.php";