<?php

use Rtgroup\HttpRouter\HttpRouter;

require_once "./vendor/autoload.php";
require_once("./controllers/autoload.php");


/**
 * Instance du composant HttpRouter
 */
$router = new HttpRouter();


/**
 * Routes listening
*/

try
{

    $router->listening(urls: ["api/uploadAll", "api/test"], controller: new TestController());
    $router->listening(urls: ["api/uploadTransaction"], controller: new TestController());

    /**
     * Fermer le router pour recuperer les erreurs 404 si existant.
     */
    $router->close();

}catch (Exception $e){
    $error=array("error"=>$e->getMessage());
    header("Content-Type:application/json");
    echo json_encode($error);
}