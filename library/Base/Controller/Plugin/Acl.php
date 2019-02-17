<?php

class Base_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
    //   Base_Acl::getInstance();
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $resource = $request->getModuleName() . '_' . $request->getControllerName() . '_' . $request->getActionName();
       // $acl = Base_Acl::getInstance();

       // $is_allow = $acl->hasAccess($resource);
       // Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole($acl->getMyRole());

//        var_dump($resource);
//        var_dump($is_allow);
//        exit();
        $is_allow = true;
        if ($is_allow === false) {
        	/*if (Base_Auth::isIdentity()) {
        		if (DEV) {
        			$message = "Forbidden. You don't have permission to access $resource resource";
        		}
        		
        		throw new Base_Exception($message, 403);
        	}*/
        	$request->setModuleName('auth');
            $request->setControllerName('index');
            $request->setActionName('index');
        }

    }

}
