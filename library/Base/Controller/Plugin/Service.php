<?php

class Base_Controller_Plugin_Service extends Zend_Controller_Plugin_Abstract
{

    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        Base_Service::setRequest($request);
    }

//    public function preDispatch(Zend_Controller_Request_Abstract $request)
//    {
//        if(!Base_Service::isIdentity()){
//            $request->setModuleName('auth');
//            $request->setControllerName('index');
//            $request->setActionName('index');
//        }
//    }

}
