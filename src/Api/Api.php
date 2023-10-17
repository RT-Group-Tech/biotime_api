<?php
/*
*Lionnel nawej
*Interaction entre biotime et millenium payroll
*/
namespace Rtgroup\BiotimeApi\Api;


use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Rtgroup\BiotimeApi\Core\Util;
use Rtgroup\BiotimeApi\Interfaces\IApi;
use Rtgroup\Dbconnect\Dbconfig;
use Rtgroup\Dbconnect\Dbconnect;
use Rtgroup\HttpRouter\DataLoader;
use Rtgroup\HttpRouter\HttpRequest;
use Rtgroup\PayrollAgences\Agences;
use Rtgroup\PayrollServices\Services;
use Rtgroup\PayrollAgents\Agents;


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
        global $dbconnect;
        $dbconnect=$this->db;

    }


    /**
     * GET JWT AUth token from Biotime authenticate
     *
     * @throws Exception
     * @throws GuzzleException
     */
    public function jwtAuthToken(): void
    {
        if(!HttpRequest::isPost())
        {
            throw new Exception("forbidden request.",404);
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
                "auth"=>$result
            ]);
        }
        else{
            $this->loadData("response", [
                "status"=>"failed",
                "auth"=>$result
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
            throw new Exception("forbidden request.",404);
        }

    }

    /**
     * GET List of all Devices
     * @throws Exception
     * @throws GuzzleException
     */
    public function getDevices(): void
    {
        $url = "http://127.0.0.1:8081/iclock/api/terminals/";
        $this->getRequest(url:$url);
    }

    /**
     * GET List of all employees
     *
     * @throws Exception
     * @throws GuzzleException
     */
    public function getEmployees(): void
    {
        $url="http://127.0.0.1:8081/personnel/api/employees/";
        $this->getRequest(url: $url);
    }

    /**
     * CREATE New employee
     *
     * @throws Exception
     * @throws GuzzleException
     */
    public function createEmployee(): void
    {
        if(!HttpRequest::isPost())
        {
            throw new Exception("forbidden request.",404);
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
        $url = 'http://127.0.0.1:8081/personnel/api/employees/';
        if($token != null){
            $result= Util::post(url:$url, data:$data, token: $token);
            $this->loadData("response", [
                "status"=>"success",
                "results"=>$result
            ]);
        }
        else{
            throw new Exception("token invalide.",203);
        }

    }

    /**
     * CREATE New Department - lionnel nawej-
     *
     * @throws Exception
     * @throws GuzzleException
     */
    public function createDepartment(): void{
   
       $url="http://127.0.0.1:8081/personnel/api/departments/";

        HttpRequest::checkRequiredData("dept_code");
        HttpRequest::checkRequiredData("nom");

        $data['dept_code']=(int)$_POST['dept_code'];
        $data['dept_name']=$_POST['nom'];
        $data['parent_dept']=null;
        //Check token du post
        $reponseBiotime=$this->postRequest($url,$data);
        if($reponseBiotime){
            /*
            * Envois des données post dans la methode services
            */
            $services=new Services();
            $reponseMillenium=$services->add();
        }  
        $this->loadDataBioMil($reponseBiotime,$reponseMillenium);
       
    }

    /**CREATE AGENCE -Lionnel nawej-
     * @throws Exception
     */
    public function createAgence():void {
       
       $agences=new Agences();
       $reponseMillenium=$agences->add();

        if($reponseMillenium){
            $this->loadData("response", [
                "status"        =>  "success",
                "response"      =>  $reponseMillenium
            ]);
        }
        else{
            $this->loadData("response", [
                "status"           => "failed",
                "message"          => "echec du traitement !"
            ]);
        }

       /**CREATION ZONE DANS BIOTIME */
       /*if($reponseMillenium){
        $url="http://127.0.0.1:8081/personnel/api/areas/";

        HttpRequest::checkRequiredData("code_zone");
        $zone =$_POST['code_zone'];
        $data = [
            "area_code" => $zone,
            "area_name" => $_POST['province'],
        ];
         //Check token du post et matching
         $reponseBiotime=$this->postRequest($url,$data);
       }*/
        // $this->loadDataBioMil($reponseBiotime,$reponseMillenium);
    }

    /**CREATE FONCTION - LIONNEL NAWEJ 11/10/2023
     * @throws Exception
     * @throws GuzzleException
     */

    public function createFonction():void{
       $agents=new Agents();

       HttpRequest::checkRequiredData("fonction_code"); //TODO: check.
       HttpRequest::checkRequiredData("libelle"); //TODO:check
       /**CHECK SI EXISTE */

       $checkExist=$agents->checkExistReturn("libelle","LIKE",$_POST['libelle'],"fonctions");
       if(is_null($checkExist)){
            $reponseMillenium=$agents->addFonction();

            // create position biotime
            $url="http://127.0.0.1:8081/personnel/api/positions/";
    
            $data['position_code']=$_POST['fonction_code'];
            $data['position_name']=$_POST['libelle'];
    
            /**CHECK TOKEN POST ET MATCHING BIOTIME */
            $reponseBiotime=$this->postRequest($url,$data); 
       }
       else{
        $this->loadData("reponse","Cette fonction existe deja");
       }
       $this->loadDataBioMil($reponseBiotime,$reponseMillenium);
    }

    /**CREATE AGENT - LIONNELNAWEJ- 11/10/2023
     * @throws Exception
     */
    public function createAgent(): void{
         
        /**CREATION EMPLOYE DANS BIOTIME*/

        HttpRequest::checkRequiredData("nom");
        HttpRequest::checkRequiredData("postnom");
        HttpRequest::checkRequiredData("prenom");
        HttpRequest::checkRequiredData("nationalite");
        HttpRequest::checkRequiredData("sexe");
        HttpRequest::checkRequiredData("date_naissance");
        HttpRequest::checkRequiredData("etat_civil");
        HttpRequest::checkRequiredData("date_engagement");
        HttpRequest::checkRequiredData("matricule");
        HttpRequest::checkRequiredData("nbre_enfant");
        HttpRequest::checkRequiredData("adresse");
        HttpRequest::checkRequiredData("contrat_type");
        HttpRequest::checkRequiredData("duree");
        HttpRequest::checkRequiredData("periode_unite");
        HttpRequest::checkRequiredData("code_zone");

        HttpRequest::checkRequiredData("agence_id"); //TODO:check
        HttpRequest::checkRequiredData("fonction_id"); //TODO: check.
        HttpRequest::checkRequiredData("service_id"); //TODO: check.
        HttpRequest::checkRequiredData("date_affectation");

        $url="http://127.0.0.1:8081/personnel/api/employees/";


        $data = [
            "emp_code" => $_POST['matricule'],
            "area" => (int)$_POST['code_zone'],
            "first_name" => $_POST['nom'],
            "last_name" => $_POST['postnom'],
            "nickname" => $_POST['prenom'],
            "gender" => $_POST['sexe'],
            "mobile" => $_POST['telephone'],
            "birthday" => $_POST['date_naissance'],
            "national" => $_POST['nationalite'],
            "address" => $_POST['adresse'],
            "email" => $_POST['email'],
            "department"=> $_POST['service_id'],
            "app_status" => 0,
            "enroll_sn" => "",
            "fingerprint" => "",
            "card_no"=> "",
            "device_password" => "",
        ];

        $agents=new Agents();
        //Check token du post
        $checkExist=$agents->checkExistReturn("matricule","LIKE",$_POST['matricule'],"agents");
        if(is_null($checkExist)){

            $reponseBiotime=$this->postRequest($url,$data); 
          
            $agent_id=$agents->addNew();
            $reponseAffecter=$agents->affecter($agent_id);

            $this->loadDataBioMil($reponseBiotime,$reponseAffecter);

        }
        $this->loadData("reponse","Cet agent existe deja");
        
    }

      /**
     * Add New Dispositif - lionnel nawej-
     **/
    public function addDispositif(): void{
        /*
         * variable globale pour gerer la bd
        */
        $agences=new Agences();
        $reponseMillenium=$agences->addDispositifs();
        $this->loadData("reponse",$reponseMillenium);
    }

    


    /**
     * List of all Department
     *
     * @throws Exception|GuzzleException
     */
    public function getDepartments(): void
    {
        $url = "http://127.0.0.1:8081/personnel/api/departments/";
        $this->getRequest(url:$url);

    }

    /**
     * CREATE New Area
     *
     * @throws Exception|GuzzleException
     */
    public function createArea(): void
    {
        if(!HttpRequest::isPost())
        {
            throw new Exception("forbidden request.",404);
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
            throw new Exception("token invalide.",203);
        }
    }

    /**
     * List of all Areas
     *
     * @throws Exception
     * @throws GuzzleException
     */
    public function getArea(): void
    {
        $url="http://127.0.0.1:8081/personnel/api/areas/";
        $this->getRequest(url: $url);
    }
      /**
     * List of agences
     *LIONNEL NAWEJ 13/10/2023
     * @throws Exception
     */
    public function getAgence(): void
    {
        /*
        $url="http://127.0.0.1:8081/personnel/api/";
        $this->getRequest(url: $url);*/
    }

    /**
     * List of agent
     *LIONNEL NAWEJ 13/10/2023
     * @throws Exception
     * @throws GuzzleException
     */
    public function getAgent(): void
    {
        $url="http://127.0.0.1:8081/personnel/api/employees/";
        $this->getRequest(url: $url);
    }

    /**
     * List of agent
     *LIONNEL NAWEJ 13/10/2023
     * @throws Exception
     * @throws GuzzleException
     */
    public function getFonction(): void
    {
        $url="http://127.0.0.1:8081/personnel/api/positions/";
        $this->getRequest(url: $url);
    }

    /**
     * List of agent
     *LIONNEL NAWEJ 13/10/2023
     * @throws Exception
     * @throws GuzzleException
     */
    public function getDepartment(): void
    {
        $url="http://127.0.0.1:8081/personnel/api/departments/";
        $this->getRequest(url: $url);
    }


    /**
     * Synchronize all devices data
     * @return void
     * @throws Exception
     * @throws GuzzleException
     */
    public function uploadAll(): void
    {
        
        $url = 'http://localhost:8081/iclock/api/terminals/upload_all/';
        $this->triggerUpload(url:$url);
    }

    /**
     * Transaction upload
     * @return void
     * @throws Exception
     * @throws GuzzleException
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
     * @throws Exception
     * @throws GuzzleException
     */
    private function getRequest(string $url) : void{
        $token = $this->getHeader('Authorization');
        if($token != null){
            $results = Util::fetch(url:$url, token: $token);
           
            if($results != null){
                $this->dispatchData($url,$results);
            }else{
                throw new Exception("resultat null.",203);
            }
            /*
            $this->loadData("response", [
                "status"=>"success",
                "datas"=>$results,
                "url" =>$url
            ]);*/
        }
        else{
            throw new Exception("token invalide.",203);
        }
    }

    /* - lionnel nawej- 10/10/2023
     * Gestion token pour les methodes en post
    */

    /**
     * @throws GuzzleException
     */
    public function postRequest($url, $data){

        $token = $this->getHeader('Authorization');
        if($token != null){
            $result= Util::post($url, data:$data, token: $token);
            return $result;
        }
        else{
            throw new Exception("token invalide.",203);
        }
    }

    /* - lionnel nawej- 10/10/2023
    * Gestion du retour loadData de la reponse biotime & millenium
    */
    public function loadDataBioMil($reponseBiotime,$reponseMillenium): void
    {
        if($reponseMillenium){

            $this->loadData("response", [
                "status"            =>"success",
                "result_biotime"    =>$reponseBiotime,
                "result_millenium"  =>$reponseMillenium
            ]);
        }else{
            $this->loadData("response", [
                "status"           =>"failed",
                "error_biotime"    =>$reponseBiotime,
                "error_millenium"  =>$reponseMillenium
            ]);
        }

    }


    /**
     * @throws Exception
     * @throws GuzzleException
     */
    private function triggerUpload(string $url):void
    {
        if(!HttpRequest::isPost())
        {
            throw new Exception("forbidden request.",404);
        }
        $data=[];

        foreach ($_POST as $key => $value) {
            $data[] = $value;
        }
        $token = $this->getHeader('Authorization');
        if($token != null){
            $result= Util::post(url:$url, data:["terminals"=>$data], token: $token);
                if($result != null){
                    $this->dispatchData($url,$result);
                }else{
                    throw new Exception("resultat null.",203);
                }
            /*
            $this->loadData("response", [
                "status"=>"success",
                "results"=>$result,
            ]);*/
        }
        else{
            throw new Exception("token invalide.",203);
        }
    }
    public function dispatchData($url,$results): void
    {
        
        $this->loadData("response", [
            "status"=>"success",
            "results"=>$results
        ]);

    }


}
