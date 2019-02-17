<?php
/**
 * Created by PhpStorm.
 * User: Robert Rogiński
 * Date: 08.05.14
 * Time: 14:09
 */
class Base_Storage_Session implements Base_Storage_Interface
{
    /**
     * Default session namespace
     */
    const NAMESPACE_DEFAULT = 'Base_Storage';

    /**
     * Session namespace
     *
     * @var mixed
     */
    protected $_namespace;


    protected $_data = array();


    /**
     * Sets session storage options and initializes session namespace object
     *
     * @param  mixed $namespace
     * @return void
     */
    public function __construct($namespace = self::NAMESPACE_DEFAULT)
    {
        $this->_namespace = $namespace;
        $this->_session   = new Zend_Session_Namespace($this->_namespace);
        $this->_data = $this->_getData();
    }




    public function add($id, $data, $type)
    {
        $this->_data[$type][$id] = $data;
        $this->_setData();

        return $this;
    }

    public function set($type, $data)
    {
        $this->_data[$type] = $data;
        $this->_setData();

        return $this;
    }




    public function getData($type)
    {
        return @$this->_data[$type];
    }


    private function _getData()
    {
        return $this->_session->__get('data');
    }


    private function _setData($data = null)
    {
        $data = null !== $data ? $data : $this->_data;

        return $this->_session->__set('data', $data);
    }


}