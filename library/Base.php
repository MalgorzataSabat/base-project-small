<?php
class Base
{
    
    static public function stripslashes_recursive($value)
    {
        return is_array($value) ? array_map('Base::stripslashes_recursive', $value) : stripslashes($value);
    }


    static public function clearCache($print_output = true)
    {
        $cache_path = Zend_Registry::get('cache_path');

        Doctrine_Lib::removeDirectories(ROOT . '/data/cache');
        Doctrine_Lib::makeDirectories(ROOT . '/data/cache', 777);

        if ($print_output) {
            echo 'Clear cache: done.';
        }
    }

    public static function getFileExt( $file )
    {
        $ext = explode( '.', $file );
        return strtolower( $ext[ count( $ext ) - 1 ] );
    }

    public static function getFileNameWithoutExt($file)
    {
        $ext = explode( '.', $file );
        unset( $ext[count( $ext ) - 1] );
        return join('.', $ext);
    }

    public static function getFileMimeType($file)
    {
        $mime_types = array(
            "pdf"=>"application/pdf"
        ,"zip"=>"application/zip"
        ,"docx"=>"application/msword"
        ,"doc"=>"application/msword"
        ,"xls"=>"application/vnd.ms-excel"
        ,"ppt"=>"application/vnd.ms-powerpoint"
        ,"bmp" => "image/bmp"
        ,"gif" => "image/gif"
        ,"png"=>"image/png"
        ,"jpeg"=>"image/jpeg"
        ,"jpg"=>"image/jpg"
        ,"mp3"=>"audio/mpeg"
        ,"mpg"=>"video/mpeg"
        ,"mpe"=>"video/mpeg"
        ,"mov"=>"video/quicktime"
        ,"avi"=>"video/x-msvideo"
        ,"3gp"=>"video/3gpp"
        ,"htm"=>"text/html"
        ,"html"=>"text/html"
        ,"txt" => "text/plain",
        );

        $ext = Base::getFileExt($file);

        return isset($mime_types[$ext]) ? $mime_types[$ext] : 'txt';
    }

    static public function getHash()
    {
        return md5( microtime() . rand( 0, 10000 ) );
    }

    static public function getFullServerName()
    {
        return self::getFullServerNameFromRequest(
            Zend_Controller_Front::getInstance()->getRequest()
        );
    }

    static public function getFullServerNameFromRequest(Zend_Controller_Request_Http $request)
    {
        $protocol = $request->isSecure() ? 'https://' : 'http://';
        $port = $request->getServer('SERVER_PORT', '80') === '80' ? '' : ':' . $request->getServer('SERVER_PORT', '80');
        return $protocol . $request->getServer('SERVER_NAME') . $port;
    }

	/**
	 * @param $router
	 * @param array $params
	 * @param bool $reset
	 * @param bool $encode
	 */
	public static function url($router = '', $params = array(), $reset = false, $encode = true)
	{
		return Base_Layout::getView()->url($params, $router, $reset, $encode);
	}

    public static function getFiledNameLabel($value, $prefix = '')
    {
        $prefix && $value = $value.'_'.$prefix;

        return 'mfn_'.$value;
    }


    public static function getFiledName($value, $prefix = '')
    {
        return Base_Layout::getView()->translate(self::getFiledNameLabel($value, $prefix));
    }


    public static function getFriendlyUri( $string, $charset = null)
    {
        if(empty($charset)){
            $charset =  defined('CHARSET') ? CHARSET : 'UTF-8';
        }

        $from = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ',  'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ',  'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ',  'ĳ',  'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ',  'œ',  'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ',  'ǽ',  'Ǿ', 'ǿ',' ', '?','"','/', ',', '„', '#', '<', '>', '.', '\'');
        $to   = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o','-', '' ,'' ,'_', '' , '' , '-', '' , '' , '',  '' );

        return mb_strtolower( str_replace( $from, $to, $string ), $charset );
    }

    public static function createDir($dir)
    {
        if( !is_dir( $dir ) ){
            mkdir($dir, MKDIR_MODE);
        }
        return $dir;
    }


    /**
     * Removes a non empty directory.
     *
     * This method recursively removes a directory and all its descendants.
     * Equivalent to 'rm -rf'.
     *
     * @param string $folderPath
     * @return boolean  success of the operation
     */
    public static function removeDirectories($folderPath)
    {
        if (is_dir($folderPath)){
            foreach (scandir($folderPath) as $value){
                if ($value != '.' && $value != '..'){
                    $value = $folderPath . "/" . $value;

                    if (is_dir($value)) {
                        self::removeDirectories($value);
                    } else if (is_file($value)) {
                        unlink($value);
                    }
                }
            }

            return rmdir($folderPath);
        } else {
            return false;
        }
    }


    /**
     * Copy all directory content in another one.
     *
     * This method recursively copies all $source files and subdirs in $dest.
     * If $source is a file, only it will be copied in $dest.
     *
     * @param string $source    a directory path
     * @param string $dest      a directory path
     * @return
     */
    public static function copy($source, $dest)
    {
        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $dest);
        }

        // Make destination directory
        if ( ! is_dir($dest)) {
            mkdir($dest, MKDIR_MODE);
        }

        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Deep copy directories
            if ($dest !== "$source/$entry") {
                self::copy("$source/$entry", "$dest/$entry");
            }
        }

        // Clean up
        $dir->close();

        return true;
    }

    /**
     * Zwraca nazwę pliku (unikalną) pozbawioną znaków specjalnych poprzedzoną prefksem
     * @param $prefix
     * @param $name nazwa pliku wraz z ext
     */
    public static function getUniqueFileName($prefix, $name, $forceExtension = null)
    {
        $prefix = $prefix ? $prefix.'_' : '';
        $unique = substr(md5(uniqid(rand(), true)), 0, 5).'_';

        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $name = pathinfo($name, PATHINFO_FILENAME);

        $name = $prefix.$unique.self::getFriendlyUri($name);

        if($ext) {
            $name .= '.'.$ext;
        } else if($forceExtension) {
            $name .= '.'.$forceExtension;
        }
        return $name;
    }

    public static function randString($length = 8, $useUpper = true, $useLower = true, $useNumber = true, $useCustom = '')
    {
        $seed = '';
        $seed .= ($useUpper ? 'ABCDEFGHKLMNPQRSTWXYZ' : '');
        $seed .= ($useLower ? 'abcdefghijkmnopqrstwxyz' : '');
        $seed .= ($useNumber ? '23456789' : '');
        $seed .= ($useCustom ? $useCustom : '');
        $seedLength = strlen($seed);
        $string = '';
        for($x = 0; $x < $length; $x++) {
            $string .= $seed[mt_rand(0, $seedLength-1)];
        }
        return $string;
    }


    static public function replaceForAlias($string)
    {
        $string = strtolower($string);
        $string = preg_replace("/[^a-zA-Z0-9\/|+ -]/", '_', $string);
        return $string;
    }

    static public function camelToDash($string)
    {
        $string = strtolower($string{0}) . substr($string, 1);
        return strtolower(preg_replace('([A-Z])', '-\\0', $string));
    }

    static public function dashToCamel($string)
    {
        $return = '';
        foreach (explode('-', $string) as $tmp) {
            $return .= ucfirst($tmp);
        }
        return $return;
    }


    public static function camelToLine($string)
    {
        $string = strtolower($string{0}) . substr($string, 1);
        return strtolower(preg_replace('([A-Z])', '_\\0', $string));
    }


    public static function lineToCamel($string)
    {
        $return = '';
        foreach (explode('_', $string) as $tmp) {
            $return .= ucfirst($tmp);
        }
        return $return;
    }

    /**
     * Method change dash to line
     * For this time use only after getFriendlyUri
     *
     * @param $string
     * @return string
     */
    public static function dashToLine($string)
    {
        $string = strtolower($string{0}) . substr($string, 1);
        return strtolower(preg_replace('/-/', '_', $string));
    }

    public static function moduleExists($module)
    {
        $frontController = Zend_Controller_Front::getInstance();
        $modulesEnabled = $frontController->getControllerDirectory();

        return array_key_exists($module, $modulesEnabled);
    }


}
