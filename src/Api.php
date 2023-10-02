<?php

namespace Biotime\Api;

use Biotime\Api\Core\Util;
use Biotime\Api\Interfaces\IApi;
use Rtgroup\Dbconnect\Dbconfig;
use Rtgroup\Dbconnect\Dbconnect;
use Rtgroup\HttpRouter\DataLoader;
use Rtgroup\HttpRouter\HttpRequest;

class Api extends IApi
{
    use DataLoader;
    private Dbconnect $db;

    private HttpRequest $request;

    public function __construct()
    {
        $dbConfig=new Dbconfig("localhost","root","","milleniumpayroll");
        $this->db = new Dbconnect($dbConfig);
        $this->request = HttpRequest::getCachedObject();
    }


    /**
     * GET JWT AUth token from Biotime authenticate
     **/
    public function jwtAuthToken(): void
    {
        if(!HttpRequest::isPost())
        {
            throw new \Exception("forbidden request.",404);
        }

        /** Verification des données entrées requises */

        HttpRequest::checkRequiredData("username",true);
        HttpRequest::checkRequiredData("password",true);


        /** @var string $url */

        $url = "http://127.0.0.1:8081/jwt-api-token-auth/";

        /** @var array $data */

        $data = [
            "username"=> $_POST['username'],
            "password"=> $_POST['password']
        ];

        $result = Util::post(url: $url, data:$data);
        if($result != null){
            $this->loadData("response", [
                "status"=>"success",
                "data"=>$result
            ]);
        }
        else{
            $this->loadData("response", [
                "status"=>"failed",
                "data"=>$result
            ]);
        }
    }


    /**
     * CREATE New Device
     **/
    public function createDevice(): void
    {
        if(!HttpRequest::isPost())
        {
            throw new \Exception("forbidden request.",404);
        }

    }

    /**
     * GET List of all Devices
     * @throws \Exception
     */
    public function getDevices(): void
    {
        $url = "http://127.0.0.1:8081/iclock/api/terminals/";
        $this->getRequest(url:$url);
    }

    /**
     * GET List of all employees
     *
     * @throws \Exception
     */
    public function getEmployees(): void
    {
        $url="http://127.0.0.1:8081/personnel/api/employees/";
        $this->getRequest(url: $url);
    }

    /**
     * CREATE New employee
     **/
    public function createEmployee(): void
    {
        if(!HttpRequest::isPost())
        {
            throw new \Exception("forbidden request.",404);
        }
        HttpRequest::checkRequiredData("matricule");
        HttpRequest::checkRequiredData("nom");
        HttpRequest::checkRequiredData("postnom");
        HttpRequest::checkRequiredData("email");
        HttpRequest::checkRequiredData("telephone");
        HttpRequest::checkRequiredData("zone");

        $data = [
            "emp_code" => $_POST['matricule'],
            "department" => 1,
            "area" => [
                ...$_POST['zone']
            ],
            "first_name" => $_POST['nom'],
            "last_name" => $_POST['postnom'],
            "nickname" => $_POST['prenom'],
            "gender" => $_POST['sexe'],
            "mobile" => $_POST['telephone'],
            "birthday" => $_POST['date_naissance'],
            "national" => $_POST['nationalite'],
            "address" => $_POST['adresse'],
            "email" => $_POST['email'],
            "app_status" => 0,
            "enroll_sn" => "",
            "fingerprint" => "",
        ];
        $token = $this->getHeader('Authorization');
        if($token != null){
            $result= Util::post('http://127.0.0.1:8081/personnel/api/employees/', data:$data, token: $token);
            $this->loadData("response", [
                "status"=>"success",
                "results"=>$result
            ]);
        }
        else{
            throw new \Exception("token invalide.",203);
        }

    }

    /**
     * CREATE New Department
     **/
    public function createDepartment(): void
    {
        if(!HttpRequest::isPost())
        {
            throw new \Exception("forbidden request.",404);
        }
        // TODO: Implement createDepartment() method.
    }

    /**
     * List of all Department
     *
     * @throws \Exception
     */
    public function getDepartments(): void
    {
        $url = "http://127.0.0.1:8081/personnel/api/departments/";
        $this->getRequest(url:$url);

    }

    /**
     * CREATE New Area
     **/
    public function createArea(): void
    {
        if(!HttpRequest::isPost())
        {
            throw new \Exception("forbidden request.",404);
        }
        HttpRequest::checkRequiredData("code_zone");
        HttpRequest::checkRequiredData("libelle_zone");

        $data = [
            "area_code" => $_POST['code_zone'],
            "area_name" => $_POST['libelle_zone'],
        ];
        $token = $this->getHeader('Authorization');
        if($token != null){
            $result= Util::post('http://127.0.0.1:8081/personnel/api/areas/', data:$data, token: $token);
            $this->loadData("response", [
                "status"=>"success",
                "results"=>$result
            ]);
        }
        else{
            throw new \Exception("token invalide.",203);
        }
    }

    /**
     * List of all Areas
     *
     * @throws \Exception
     */
    public function getAreas(): void
    {
        $url="http://127.0.0.1:8081/personnel/api/areas/";
        $this->getRequest(url: $url);
    }


    /**
     * Synchronize all devices data
     * @return void
     * @throws \Exception
     */
    public function uploadAll(): void
    {

        $url = 'http://localhost:8081/iclock/api/terminals/upload_all/';
        $this->triggerUpload(url:$url);
    }

    /**
     * Transaction upload
     * @return void
     * @throws \Exception
     */
    public function uploadTransaction(): void
    {

        $url = 'http://localhost:8081/iclock/api/terminals/upload_transaction/';
        $this->triggerUpload(url:$url);

    }

    /**
     * Calcul les données des presences des agents pour les statistiques
     * @return void
     */
    public function calculatePresence(): void
    {
        // TODO: Implement calculatePresence() method.
    }


    /**
     * Renvoie les données envoyées
     * @param string $key
     * @return mixed
     */
    private function getHeader(string $key): mixed{
        $headers = getallheaders();
        if(!isset($headers[$key])){
            return null;
        }
        return $headers[$key];
    }


    /**
     * Execute GET Request to load datas from Biotime API
     * @param string $url
     * @return void
     * @throws \Exception
     */
    private function getRequest(string $url) : void{
        $token = $this->getHeader('Authorization');

        if($token != null){
            $results = Util::fetch(url:$url, token: $token);
            $this->loadData("response", [
                "status"=>"success",
                "datas"=>$results
            ]);
        }
        else{
            throw new \Exception("token invalide.",203);
        }
    }


    private function triggerUpload(string $url):void
    {
        if(!HttpRequest::isPost())
        {
            throw new \Exception("forbidden request.",404);
        }
        $data=[];

        foreach ($_POST as $key => $value) {
            $data[] = $value;
        }
        $token = $this->getHeader('Authorization');
        if($token != null){
            $result= Util::post(url:$url, data:["terminals"=>$data], token: $token);
            $this->loadData("response", [
                "status"=>"success",
                "results"=>$result
            ]);
        }
        else{
            throw new \Exception("token invalide.",203);
        }
    }


}