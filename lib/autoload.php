<?php

/*
 **************************************************
 ** autoloader
 **************************************************
 **
 ** fonction auto appelé, permet d'auto chargé les class PHP
 ** on a besoin du dossier ou se trouve la class, donné par le namespace
 ** ainsi que du nom de la class qui correspond au nom du fichier
 ** une extension de fichier est défini par défaut ici: .class.php
 **
 ** Exemples:
 ** 
 ** - call -> new lib\App()
 ** - load -> ./lib/App.class.php
 **
 ** - call -> new chemin\vers\MaClass()
 ** - load -> ./chemin/vers/MaClass.class.php
 **
 **************************************************
 */

(function () {

	$sFileExt = ".class.php";

	$success = spl_autoload_register(function (string $sName) use ($sFileExt) {

		$sPath = str_replace("\\", "/", $sName);
		$sFullPath = App()->mkPath($sPath . $sFileExt);

		if (!file_exists($sFullPath)) {
			LOGERROR("file %s does not exist", $sFullPath);
			return;
		}

		require $sFullPath;

		try {

			$oReflection = new ReflectionClass($sName);
			$sType = $oReflection->isInterface() ? "interface" : "class";

			if ($sType === "interface") {
				if (!interface_exists($sName)) {
					LOGERROR("interface %s does not exist", $sName);
					return;
				}
			} else {
				if (!class_exists($sName)) {
					LOGERROR("class %s does not exist", $sName);
					return;
				}
			}

			// LOGDEBUG("[AUTOLOAD] loading %s: %s", $sType, $sName);

			
		} catch (Throwable $th) {
			throw $th;
		}

	});

	if (!$success) {
		LOGERROR("can't call function spl_autoload_register");
	}

})();