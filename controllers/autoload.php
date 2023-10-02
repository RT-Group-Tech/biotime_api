<?php

$mainDir=dirname($_SERVER['SCRIPT_FILENAME']);
$controllersFolder=$mainDir.DIRECTORY_SEPARATOR."controllers";
$files=scandir($controllersFolder);

/**
 *  inclure tout les controllers directement.
 */
for($i=0; $i<count($files); $i++)
{
    if(strstr($files[$i],".php"))
    {
        /**
         * Inclure.
         */
        require_once ($controllersFolder.DIRECTORY_SEPARATOR.$files[$i]);
    }
}