<?php
class Auth_Acl
{
    /**
     * @var Base_Acl
     */
    protected $_acl;

    /**
     * @param $acl Base_Acl
     */
    public function __construct($acl)
    {
        $this->_acl = $acl;
    }


    public function loadAcl()
    {
        $this->_acl->addResource('auth_index_recover-password', '_basic');
        $this->_acl->addResource('auth_index_change-pass', '_basic');
        $this->_acl->addResource('auth_index_logout', '_basic');
        $this->_acl->addResource('auth_index_index', '_basic');

        return $this->_acl;
    }


}