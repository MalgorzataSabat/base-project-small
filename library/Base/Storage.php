<?php
/**
 * Created by PhpStorm.
 * User: Robert Rogiński
 * Date: 08.05.14
 * Time: 14:55
 */
class Base_Storage
{
    /**
     * Singleton instance
     *
     * @var Base_Storage
     */
    protected static $_instance = null;

    /**
     * Persistent storage handler
     *
     * @var Base_Storage_Interface
     */
    protected $_storage = null;

    /**
     * Singleton pattern implementation makes "new" unavailable
     *
     * @return void
     */
    protected function __construct()
    {}

    /**
     * Singleton pattern implementation makes "clone" unavailable
     *
     * @return void
     */
    protected function __clone()
    {}

    /**
     * Returns an instance of Base_Storage
     *
     * Singleton pattern implementation
     *
     * @return Base_Storage Provides a fluent interface
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }


    /**
     * Returns an instance of Base_Storage
     *
     * Singleton pattern implementation
     *
     * @return Base_Storage Provides a fluent interface
     */
    public static function _()
    {
        return self::getInstance();
    }


    /**
     * Returns the persistent storage handler
     *
     * Session storage is used by default unless a different storage adapter has been set.
     *
     * @return Base_Storage_Interface
     */
    public function getStorage()
    {
        if (null === $this->_storage) {
            /**
             * @see Base_Storage_Session
             */
            require_once 'Base/Storage/Session.php';
            $this->setStorage(new Base_Storage_Session());
        }

        return $this->_storage;
    }

    /**
     * Sets the persistent storage handler
     *
     * @param  Base_Storage_Interface $storage
     * @return Base_Storage Provides a fluent interface
     */
    public function setStorage(Base_Storage_Interface $storage)
    {
        $this->_storage = $storage;

        return $this;
    }


    public function add($id, $data, $type)
    {
        $this->getStorage()->add($id, $data, $type);

        return $this;
    }


    public function set($type, $data)
    {
        $this->getStorage()->set($type, $data);

        return $this;
    }



    public function getData($type)
    {

        return $this->getStorage()->getData($type);
    }

}
