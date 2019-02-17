<?php

class Base_Tool_Project_Provider_Doctrine extends Zend_Tool_Project_Provider_Abstract
{
	static $msg_counter = 0;
	private $timer = 0;

	/**
	 * @var Zend_Application
	 */
	private $app;

	public function __construct()
	{
		$this->app = Zend_Registry::get('application');
		$this->_resetTimer();
	}


	public function build()
	{
		$this->_print("\f Make Build Procedure", false);
		$this->_print("======================", false);
		$this->app->getBootstrap()->bootstrap('autoload');
		$this->app->getBootstrap()->bootstrap('database');
		$this->_print('Provider init');

        $file = APPLICATION_PATH . '/config/database.php';
        $conn = include $file;
	
        // deleted generated models
        $ymlFiles = array();
        $frontController = Zend_Controller_Front::getInstance();
        $this->app->getBootstrap()->bootstrap('modules');
        $modulesEnabled = $frontController->getControllerDirectory();

        foreach( $modulesEnabled as $module => $controllerPath ){
            $schema_file = $frontController->getModuleDirectory( $module ) . DS . 'config' . DS . 'schema.yml';
            if(file_exists($schema_file)){
                $ymlFiles[] = $schema_file;
                $this->_print('Read Yaml Module: '.$module);
            }
        }

        Base_Doctrine::generateModelsFromYaml( $ymlFiles, APPLICATION_PATH.'/models', $conn );

		// delete old not needed models
		$this->_print('Deleting unused models');

        foreach( $modulesEnabled as $module => $controllerPath ){
            $models_dir = $frontController->getModuleDirectory( $module ).DS.'/models';
            if(is_dir($models_dir)){
                foreach (scandir($models_dir) as $file)
				{
                    if ($file{0} == '.' || pathinfo($file, PATHINFO_EXTENSION) !== 'php') continue;
                    if (file_exists($models_dir . '/Table/' . $file) === false) {
                        $this->_print("\t file /Table/$file not found");
                        unlink($models_dir . '/' . $file);
                        $this->_print("\t\t file $file deleted");
                    }else{
						chmod($models_dir . '/Table/' . $file, 0666);
					}
                }
            }
        }

		$this->_print('Generated models successfully from YAML schema');
	}

	private function _print($message, $show_timer = true)
	{
		if ($show_timer) {
			$time = $this->__microtime_float() - $this->timer;
			$this->_registry->getResponse()->appendContent(" [" . number_format($time, 2) . " sec] " . $message, array('color' => 'hiGreen'));
			$this->_resetTimer();
		} else {
			$this->_registry->getResponse()->appendContent($message, array('color' => 'hiGreen'));
		}
	}

	private function _resetTimer()
	{
		$this->timer = $this->__microtime_float();
	}

	private function __microtime_float()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

}

