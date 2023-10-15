<?php

namespace Rtgroup\BiotimeApi\Interfaces;

abstract class IApi
{
    /**
     * GET JWT AUth token from Biotime authenticate
     * @return void
     **/
    public abstract  function jwtAuthToken():void;


    /**
     * CREATE New Device
     * @return void
     **/
    public abstract  function createDevice():void;


    /**
     * GET List of all Devices
     * @return void
     */
    public abstract  function getDevices():void;


    /**
     * GET List of all employees
     * @return void
    **/
    public abstract function getEmployees():void;


    /**
     * CREATE New employee
     * @return void
     **/
    public abstract function createEmployee():void;


    /**
     * CREATE New Department
     * @return void
     **/
    public abstract function createDepartment():void;

     /**
     * CREATE New agence
     * @return void
     **/
    public abstract function createAgence():void;

     /**
     * CREATE New Agents
     * @return void
     **/
    public abstract function createAgent():void;
    /**
     * CREATE New fonction
     * @return void
     **/
    public abstract function createFonction():void;

       /**
     * Add dispositifss
     * @return void 
     **/
    public abstract function addDispositif():void;




    /**
     * List of all Department
     * @return void
     **/
    public abstract function getDepartments():void;



    /**
     * CREATE New Area
     * @return void
     **/
    public abstract function createArea():void;

    /**
     * Synchronize all devices data
     * @return void
    */
    public abstract function uploadAll():void;

    /**
     * Transaction upload
     * @return void
     */
    public abstract function uploadTransaction():void;

    /**
     * Calcul les données des presences des agents pour les statistiques
     * @return void
     */
    public  abstract  function calculatePresence():void;


    /**
     * Les données de recuperation de departement
     * @return void
     */
    public  abstract  function getDepartment():void;

    /**
     * Les données de recuperation des agences
     * @return void
     */
    public  abstract  function getAgence():void;

    /**
     * Les données de recuperation des agents
     * @return void
     */
    public  abstract  function getAgent():void;

    /**
     * Les données de recuperation des fonctions
     * @return void
     */
    public  abstract  function getFonction():void;

    /**
     * Les données de recuperation des zones
     * @return void
     */
    public  abstract  function getArea():void; 

}
