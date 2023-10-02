<?php
/**
 * Class gérant la configuration de la configuration d'une base de donnée.
 */
namespace Rtgroup\Dbconnect;

class Dbconfig
{
    public $dbHostname="localhost"; /** host name */
    public $dbName=""; /** nom de la db */
    public $dbUsername=""; /**nom d'utilisateur de la db*/
    public $dbUserPassword=""; /**mot de pass de la bd */


    public function __construct($dbHostname,$dbUsername,$dbUserPassword,$dbName)
    {
        $this->dbHostname=$dbHostname;
        $this->dbUsername=$dbUsername;
        $this->dbUserPassword=$dbUserPassword;
        $this->dbName=$dbName;
    }
}