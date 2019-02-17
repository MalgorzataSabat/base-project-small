<?php

class Base_Tool_Project_Provider_Label extends Base_Tool_Project_Provider_Abstract
{
    public function __construct()
    {
        $this->_resetTimer();
        $this->app = Zend_Registry::get('application');
        $this->app->getBootstrap()->bootstrap('autoload');
        $this->app->getBootstrap()->bootstrap('database');
    }

    public function export($module = null, $lang = 'pl')
    {
        Base_I18n::setRequest(new Zend_Controller_Request_Http());
        Base_I18n::setContentLanguage($lang);

        $this->_print("\f Start Export",false);

        $service = new Admin_Label_Export();
        $service->start($module);

        $this->_print("\n\f Finish Export",false);
    }

    public function import($module = null, $file = null, $lang = 'pl')
    {
        Base_I18n::setRequest(new Zend_Controller_Request_Http());
        Base_I18n::setContentLanguage($lang);

        $this->_print("\f Start Import",false);

        $service = new Admin_Label_Import();
        $service->start($module,$file);

        $this->_print("\n\f Finish Import",false);
    }

    public function diff($lang = 'pl')
    {
        Base_I18n::setRequest(new Zend_Controller_Request_Http());
        Base_I18n::setContentLanguage($lang);

        $this->_print("\f Label diff:",false);

        $service = new Admin_Label_Diff();
        $service->start();
    }
}