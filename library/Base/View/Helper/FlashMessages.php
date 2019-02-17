<?php

class Base_View_Helper_FlashMessages
{

    public function flashMessages()
    {
        return Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getMessages();
    }
}