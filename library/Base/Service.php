<?php

class Base_Service
{
    /**
     * @var bool
     */
    private static $first_run = false;

    /**
     * @var array
     */
    private static $service = null;

    /**
     * @var integer id_service
     */
    private static $id_service = null;


    /**
     * @param Zend_Controller_Request_Abstract $request
     * @throws Exception
     */
    public static function setRequest(Zend_Controller_Request_Abstract $request)
    {
        if (self::$first_run !== false) {
            throw new Exception(__CLASS__.'::setRequest is allowed to run only once.');
        }


        self::$first_run = true;
    }


    /**
     * @param null $col
     * @return Service or column Service
     * @throws Exception
     */
    public static function getService($col = null)
    {
        if($col !== null && isset(self::$service[$col])){
            return self::$service[$col];
        }


        return self::$service;
    }

    /**
     * Return id_base
     * @return string
     */
    public static function getId()
    {
        return self::getService('id_service');
    }

    /**
     * @return boolean
     */
    public static function isIdentity()
    {
        return (bool)self::$id_service;
    }

}