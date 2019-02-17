<?php
class Base_Loader_Doctrine{

    private static $_models_resorces = null;

    private static $_cache = null;

    private static $_cache_name = 'models_loader';

	public static function modelsAutoload($className) {
		if( class_exists( $className, false ) || interface_exists( $className, false ) ) {
			return false;
		}

        if(self::$_cache === null){
            self::$_cache = Base_Cache::getCache('default');
            self::$_models_resorces = self::$_cache->load(self::$_cache_name);
        }

        if(!isset(self::$_models_resorces[$className])){
            $frontController = Zend_Controller_Front::getInstance();
            $modulesEnabled = $frontController->getControllerDirectory();

            foreach($modulesEnabled as $module => $controllerPath){
                $modelPath = $frontController->getModuleDirectory($module).DS.'models';
                $class = $modelPath . DS . str_replace ( '_', DIRECTORY_SEPARATOR, $className ) . '.php';
                if( file_exists($class) ) {
                    require $class;
                    self::$_models_resorces[$className] = $class;
                    self::$_cache->save(self::$_models_resorces, self::$_cache_name);

                    return true;
                }
            }
        }else{
            require self::$_models_resorces[$className];

            return true;
        }

		return false;
	}
}