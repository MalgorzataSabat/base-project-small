<?php
class Base_Cache
{
    /**
     * @var Zend_Cache_Manager
     */
    private static $_cacheManager;

    public static $deleted_files = array();
    public static $error_files = array();



    /**
     * @param  string $name
     * @return Zend_Cache_Core
     */
    public static function getCache($name = 'default')
    {
        if (null === self::$_cacheManager) {
            self::$_cacheManager = self::getCacheManager();
        }

        return self::$_cacheManager->getCache($name);
    }

    public static function setCacheManager($cacheManager)
    {
        self::$_cacheManager = $cacheManager;
    }

    /**
     * @return Zend_Cache_Manager
     */
    public static function getCacheManager()
    {
        if (null === self::$_cacheManager) {
            throw new Exception('CacheManager not set!');
        }

        return self::$_cacheManager;
    }


    public static function clean()
    {
        $dir = self::getCache()->getBackend()->getOption('cache_dir');
        self::$deleted_files = array();
        self::$error_files = array();

        self::_clearDir($dir);
    }

    private static function _clearDir($dir) {
        foreach( scandir($dir) as $file ) {
            if( $file === '.' || $file === '..' || $file === '.empty' ) {
                continue;
            }
            $fullname = rtrim($dir, DS).DS.trim($file, DS);

            if( is_dir($fullname) === true ) {
                self::_clearDir($fullname);
                if( @rmdir($fullname) === false ) {
                    self::$error_files[] = str_replace(ROOT_PATH,null, $fullname);
                } else {
                    self::$deleted_files[] = str_replace(ROOT_PATH,null, $fullname);;
                }
            } else {
                if( @unlink($fullname) === true ) {
                    self::$deleted_files[] = str_replace(ROOT_PATH,null, $fullname);;
                } else {
                    self::$error_files[] = str_replace(ROOT_PATH,null, $fullname);;
                }
            }

        }
    }

}
