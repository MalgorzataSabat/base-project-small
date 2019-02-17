<?php

class Base_View_Helper_ModifiedDate extends Zend_View_Helper_Abstract {
    
    public function modifiedDate($path, $dir = null)
    {
        if (empty($path)) {
            return null;
        }
        
        if(null == $dir) {
            $dir = APPLICATION_WEB;
        }
        
        if(false !== strpos($path, '?')) {
            return $path;
        }

        $fpath = $dir.$path;
        if(!file_exists($fpath)) {
            return $path;
        }

        $mtime = filemtime($fpath);
        if(false === $mtime) {
            return $path;
        }
        
        return $path.'?mtime='.$mtime;
    }
}