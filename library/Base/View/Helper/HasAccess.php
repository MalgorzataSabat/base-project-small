<?php

class Base_View_Helper_HasAccess extends Zend_View_Helper_Abstract {


    public function hasAccess($resource, $privilege = null, $role = null)
    {
        return Base_Acl::getInstance()->hasAccess($resource, $privilege, $role);
    }
}