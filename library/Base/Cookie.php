<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.11.2017
 * Time: 12:52
 */

class Base_Cookie
{

    /**
     * @var Base_Cookie
     */
    protected static $_instance = null;

    public $_data = array();


    protected $_lifetime = 3600 * 24 * 365;// 1 rok

    protected $_cookieName = 'userSettings';

    protected function __construct()
    {
        if(isset($_COOKIE[$this->_cookieName])){
            $this->_data = (array) @json_decode($_COOKIE[$this->_cookieName], true);
        }

    }

    /**
     * @return Task_TimeService
     */
    protected static function getInstance()
    {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new Base_Cookie();
        }

        return self::$_instance;
    }


    /**
     * @return Base_Cookie
     */
    public static function _()
    {
        return self::getInstance();
    }


    public function get($name, $default = null)
    {
        if(isset($this->_data[$name])){
            return $this->_data[$name];
        }

        return $default;
    }

    public function set($name, $value)
    {
        $this->_data[$name] = $value;
        $this->_set();

        return $this;
    }



    private function _set()
    {
        setcookie($this->_cookieName, json_encode($this->_data), time()+$this->_lifetime, '/');
    }

}
