<?php

namespace Rtgroup\BiotimeApi\Core;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Rtgroup\Dbconnect\Dbconfig;
use Rtgroup\Dbconnect\Dbconnect;

class Util
{
    /**
     * @var Dbconnect $db database instance
     */
    private Dbconnect $db;


    public function __construct()
    {
        $dbConfig=new Dbconfig("localhost","root","","milleniumpayroll");
        $this->db=new Dbconnect($dbConfig);
    }

    /**
     * Appel les requetes GET de biotime
     * @param string $url biotime api url
     * @return mixed biotime jsonData
     *
     * @throws GuzzleException
     */
    public static function fetch(string $url, $token=null) :mixed
    {
        $client = new Client();
        $response = $client->request(method: 'GET', uri: $url,options: [
            'headers' => [
                'Authorization'=>$token,
                'Accept'     => 'application/json',
            ]
        ]);
        return json_decode($response->getBody());
    }


    /**
     * Appel les request POST de biotime
     * @param string $url biotime api url
     * @return mixed biotime jsonData
     *
     * @throws GuzzleException
     */

    public static function post(string $url, Array $data, $token=null) : mixed
    {
        $client = new Client();
        $response = $client->request(method: 'POST', uri: $url,options: [
            'headers' => [
                'Authorization'=>$token,
                'Accept'     => 'application/json',
            ],
            'form_params'=>$data
        ]);
        return json_decode($response->getBody());
    }
}