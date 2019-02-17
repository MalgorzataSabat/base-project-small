<?php
/**
 * @see Zend_Acl_Role_Interface
 */
require_once 'Zend/Acl/Role/Interface.php';

class Base_Acl_Role implements Zend_Acl_Role_Interface
{
    /**
     * Unique id of Role
     *
     * @var string
     */
    protected $_roleId;

    /**
     * Sets the Role identifier
     *
     * @param  string $roleId
     */
    public function __construct($roleId, $user)
    {
        $this->_roleId = (string) $roleId;
        if($user){
            foreach($user as $k => $v){
                $this->$k = $v;
            }
        }

    }

    /**
     * Defined by Zend_Acl_Role_Interface; returns the Role identifier
     *
     * @return string
     */
    public function getRoleId()
    {
        return $this->_roleId;
    }

    /**
     * Defined by Zend_Acl_Role_Interface; returns the Role identifier
     * Proxies to getRoleId()
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getRoleId();
    }
}
