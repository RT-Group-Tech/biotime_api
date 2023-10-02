<?php

namespace Biotime\Api\Interfaces;

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
     * List of all Areas
     * @return void
     **/
    public abstract function getAreas():void;



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

}