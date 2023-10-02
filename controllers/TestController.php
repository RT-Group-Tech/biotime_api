<?php

use Biotime\Api\Api;
use Rtgroup\HttpRouter\Controller;
use Rtgroup\HttpRouter\HttpRequest;

class TestController extends Controller{

    /**
     * @var HttpRequest Pour faire des requêtes Http
     */
    private HttpRequest $httpRequest;


    /**
     * @var Api Pour acceder à l'Api Biotime
     */
    private Api $api;

    public function __construct()
    {
        $this->api = new Api();
    }

    /**
     * @throws Exception
     */
    public function captured($url, $httpRequest, array $params = null):void
    {
        $this->httpRequest=$httpRequest;

        switch($url)
        {
            case "api/uploadAll": $this->api->uploadAll(); break;
            case "api/uploadTransaction": $this->api->uploadTransaction(); break;
        }
    }
}