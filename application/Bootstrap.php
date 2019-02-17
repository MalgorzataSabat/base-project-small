<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initAutoload()
    {
        require_once 'Doctrine.php';
		Zend_Loader_Autoloader::getInstance()
            ->pushAutoloader(array('Doctrine', 'autoload'))
            ->pushAutoloader(array('Base_Loader_Doctrine', 'modelsAutoload'))
            ->registerNamespace('Base')
            ->registerNamespace('Doctrine');

        $this->setResourceLoader(new Base_Application_Module_Autoloader(array(
            'namespace' => 'Default',
            'basePath' => APPLICATION_PATH,
        )));
    }


    protected function _initCache()
    {
        $this->bootstrap('cachemanager');
        $cacheManager = $this->getResource('cachemanager');
        Base_Cache::setCacheManager($cacheManager);

        $cache = Base_Cache::getCache('default');
        Zend_Registry::set('Zend_Cache', $cache); // Save in registry

        return $cache; // Save in bootstrap
    }

	public function _initDatabase()
    {
        $conn = include APPLICATION_PATH . '/config/database.php';
		Doctrine_Manager::connection($conn['connection'], 'base')->setCharset('utf8');
        foreach($conn['attributes'] as $attr => $value){
            Doctrine_Manager::getInstance()->setAttribute($attr, $value);
        }
	}



    protected function _initLayout()
    {
        $this->bootstrap('frontController');
        $layout = Base_Layout_Provider::startMvc();
        if(!CLI){ Base_Layout::loadLayouts(); }

        return $layout;
    }


   protected function _initZFDebug()
    {
        if(!ZFDEBUG_USE){
            return;
        }

        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('ZFDebug');

        $options = array(
            'plugins' => array(
                'Variables',
                'Html',
                'ZFDebug_Controller_Plugin_Debug_Plugin_Doctrine',
                'File' => array('basePath' => APPLICATION_PATH ),
                'Memory',
                'Time',
                'Registry',
//                'Cache' => array('backend' => Base_Cache::getCache() ),
                'Exception')
        );

        $debug = new ZFDebug_Controller_Plugin_Debug($options);

        $this->bootstrap('frontController');
        $frontController = $this->getResource('frontController');
        $frontController->registerPlugin($debug);
    }


}
