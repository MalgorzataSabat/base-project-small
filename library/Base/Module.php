<?php
/**
 * Created by PhpStorm.
 * User: Robert Rogiński
 * Date: 25.07.14
 * Time: 10:11
 */
class Base_Module
{


    /**
     * Method return path to given module
     * @param $module
     * @return null|string
     */
    public static function getModulePath($module)
    {
        return Zend_Controller_Front::getInstance()->getModuleDirectory($module);
    }

    public static function getModules()
    {
        $modulesEnable = array();
        $modulesAllow = Zend_Controller_Front::getInstance()->getControllerDirectory();

        foreach ($modulesAllow as $module_name => $path) {
            $modulesEnable[$module_name] = $module_name;
        }

        return $modulesEnable;
    }

    /**
     * Method get all modules and their version from application
     * @return array
     */
    public static function getModulesVersion()
    {
        $modulesEnable = array();
        $modulesAllow = Zend_Controller_Front::getInstance()->getControllerDirectory();

        foreach ( $modulesAllow as $module_name => $path ) {
            $filename = self::getModulePath($module_name).DS.'version.txt';
            $modulesEnable[$module_name] = array();
            if ( file_exists($filename) ) {
                $modulesEnable[$module_name] = array('version' => file_get_contents($filename));
            }
        }

        return $modulesEnable;
    }

    /**
     * Method return version of given module
     * @param $module
     * @return string
     */
    public static function getModuleVersion($module)
    {
        $version = '';
        $filename = self::getModulePath($module).DS.'version.txt';
        if ( file_exists($filename) ) {
            $version = file_get_contents($filename);
        }

        return $version;
    }

    /**
     * Method return changelog of given module
     * @param $module
     * @return null|string
     */
    public static function getModuleChangelog($module)
    {
        $filename = self::getModulePath($module).DS.'CHANGELOG.md';
        $changelog = null;
        if ( file_exists($filename) ) {
            $changelog = file_get_contents($filename);
        }

        return $changelog;
    }
}