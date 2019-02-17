<?php

class Base_Tool_Project_Provider_Extract extends Base_Tool_Project_Provider_Abstract
{
    public function __construct()
    {
        $this->_resetTimer();
        $this->app = Zend_Registry::get('application');
        $this->app->getBootstrap()->bootstrap('autoload');
        $this->app->getBootstrap()->bootstrap('database');
    }

    /**
     * @param string $module ALL
     */
    public function db($module = 'default|all')
    {
        $this->_print("\f Start Extract",false);

        $frontController = Zend_Controller_Front::getInstance();
        $this->app->getBootstrap()->bootstrap('modules');
        $modulesEnabled = $frontController->getControllerDirectory();
        $dumpModules = array($module);

        if($module == 'all'){
            $dumpModules = array_keys($modulesEnabled);
        }

        foreach($dumpModules as $module){
            if(!array_key_exists($module, $modulesEnabled)){
                $this->_print('Module ('.$module.') NOT Exists');
                return;
            }

            $extractorClassName = $module != 'default' ? ucfirst($module).'_Extractor' : 'Base_Extractor_Default';

            if(class_exists($extractorClassName)){
                $extractorClass = new $extractorClassName();

                $extractorClass->extract();
                $this->_print('EXTRACT Module ('.$module.') DONE');
            }
        }


        exit();
    }

}