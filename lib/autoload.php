<?php

(function () {

	$sFileExtension = ".php";

	$success = spl_autoload_register(function (string $sName) use ($sFileExtension) {

		$sPath = str_replace("\\", "/", $sName);
		$sFullPath = App()->mkPath($sPath . $sFileExtension);

		if (!file_exists($sFullPath)) {
			throw new Exception("file $sFullPath does not exist");
		}

		require $sFullPath;

		try {

			$oReflection = new ReflectionClass($sName);
			$sType = $oReflection->isInterface() ? "interface" : "class";

			if ($sType === "interface") {
				if (!interface_exists($sName)) {
					throw new Exception("interface $sName does not exist");
				}
			} else {
				if (!class_exists($sName)) {
					throw new Exception("class $sName on file $sFullPath does not exist");
				}
			}

			// LOGDEBUG("[AUTOLOAD] loading %s: %s", $sType, $sName);

		} catch (Throwable $th) {
			throw $th;
		}

	});

	if (!$success) {
		throw new Exception("failure on function spl_autoload_register");
	}

})();