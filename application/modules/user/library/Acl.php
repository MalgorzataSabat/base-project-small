<?php
class User_Acl
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
        $this->_acl->addResource('user_account_change-pass', '_user');
        $this->_acl->addResource('user_account_manage-emails', '_user');
        $this->_acl->addResource('user_account_confirm-new-email', '_user');
        $this->_acl->addResource('user_account_setting', '_user');
        $this->_acl->addResource('user_account_generate-link', '_user');
        $this->_acl->addResource('user_index_signature', '_user');

        return $this->_acl;
    }


}