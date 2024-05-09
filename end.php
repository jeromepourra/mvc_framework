<?php
/*
**************************************************
** End
**************************************************
**
** finalisation de l'application
** toutes les pages finissent par ce fichier
**
**************************************************
*/

Document()->setBody(BufferOff());
require App()->mkTemplatePath("layout/layout.tpl.php");