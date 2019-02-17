<?php

class Base_Controller_Plugin_I18n extends Zend_Controller_Plugin_Abstract
{
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        Base_I18n::setRequest($request);
    }
}

