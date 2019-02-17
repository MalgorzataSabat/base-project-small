<?php
class Layout_Acl
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
        $this->_acl->addResource('layout_index_new', '_user');
        $this->_acl->addResource('layout_index_edit', '_user');
        $this->_acl->addResource('layout_index_widget-list', '_user');
        $this->_acl->addResource('layout_index_widget-get', '_user');
        $this->_acl->addResource('layout_index_load-template', '_user');

        return $this->_acl;
    }


}