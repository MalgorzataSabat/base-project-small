<?php

class Base_Tool_Project_Provider_Cache extends Zend_Tool_Project_Provider_Abstract
{
	/**
	 * @var Zend_Application
	 */
	private $app;
	
	public function __construct()
    {
		$this->app = Zend_Registry::get('application');
	}
	
	public function clean()
    {
        $this->app->getBootstrap()->bootstrap('autoload');
        $this->app->getBootstrap()->bootstrap('cache');

        Base_Cache::clean();
		if( count(Base_Cache::$deleted_files) !== 0 ) {
			$this->_registry->getResponse()->appendContent('Usunięte pliki i katalogi:', array('color'=>'hiBlue'));
			foreach(Base_Cache::$deleted_files as $file) {
				$this->_registry->getResponse()->appendContent('   '.$file, array('color'=>'hiGreen'));
			}
		} else {
            $this->_registry->getResponse()->appendContent('Usunięte pliki i katalogi:', array('color'=>'hiBlue'));
            $this->_registry->getResponse()->appendContent('    nie usunięto żadnego pliku ani katalogu.', array('color'=>'hiRed'));
        }
        if( count(Base_Cache::$error_files) !== 0 ) {
            $this->_registry->getResponse()->appendContent('Pliki i katalogi, których nie udało się usunąć: ', array('color'=>'hiBlue'));
            foreach(Base_Cache::$error_files as $file) {
                $this->_registry->getResponse()->appendContent('   '.$file, array('color'=>'hiRed'));
            }
        }
	}
}

