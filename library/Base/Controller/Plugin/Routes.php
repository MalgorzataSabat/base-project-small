<?php
/**
 * Created by PhpStorm.
 * User: Dawid Przygodzki
 * Date: 2015-02-20
 * Time: 09:42
 */

class Base_Controller_Plugin_Routes extends Zend_Controller_Plugin_Abstract
{
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        $frontController = Zend_Controller_Front::getInstance();
        $frontController->getRouter()->removeDefaultRoutes();
        $modulesEnabled = $frontController->getControllerDirectory();

        $cache = Base_Cache::getCache('default');
        $_router_cache_name = 'routes_'.Base_I18n::getLangCode();

        if(Base_Service::isIdentity()){
            $_router_cache_name.= '_'.Base_Service::isIdentity();
        }

        if(Base_Auth::isIdentity()){
            $_router_cache_name.= '_'.Base_Auth::getUserId();
        }

        $_router_resorces = $cache->load( $_router_cache_name );

        if( $_router_resorces === false ){
            foreach( $modulesEnabled as $module => $controllerPath ){
                $routerDir = $frontController->getModuleDirectory( $module ) . DS . 'router';
                if(file_exists($routerDir) && is_dir($routerDir)){
                    foreach (scandir($routerDir) as $file){
                        $full_path_file = $routerDir . DS . $file;
                        if( $file{0} !== '.' && is_file($full_path_file) === true && substr($file, strrpos($file, ".")) === '.php' ){
                            require_once $full_path_file;
                        }
                    }
                }
            }

            $cache->save( $frontController->getRouter()->getRoutes(), $_router_cache_name );
        }else{
            $frontController->getRouter()->addRoutes($_router_resorces);
        }
    }
}