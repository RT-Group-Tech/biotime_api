<?php

namespace Biotime\Api\Core;

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
     **/
    public static function fetch(string $url, $token=null):mixed
    {
        $options=array(
            'http' => array(
                'method'  => 'GET',
                'header'  => $token !==null
                    ? "Content-type: application/json\r\n"."Authorization: $token"
                    : "Content-type: application/json\r\n",
            ),
        );
        $context = stream_context_create(options: $options);
        $results = file_get_contents($url, false, $context);
        if(!$results){
            return null;
        }
        return json_decode($results);
    }



    /**
     * Appel les request POST de biotime
     * @param string $url biotime api url
     * @return mixed biotime jsonData
     **/

    public static function post(string $url, Array $data, $token=null) : mixed
    {
        $options=array(
            'http' => array(
                'method'  => 'POST',
                'header'  => $token !==null
                    ? "Content-type: application/x-www-form-urlencoded\r\n"."Authorization: $token"
                    : "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($data)
            ),
        );
        $context = stream_context_create(options: $options);
        $results= file_get_contents($url,false,$context);
        if(!$results){
            return null;
        }
        return json_decode($results);
    }

}