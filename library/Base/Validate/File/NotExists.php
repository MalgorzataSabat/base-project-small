<?php

require_once 'Zend/Validate/File/Exists.php';

/**
 * Validator which checks if the destination file does not exist
 *
 * @category  Zend
 * @package   Zend_Validate
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */
class Base_Validate_File_NotExists extends Zend_Validate_File_Exists
{
    /**
     * @const string Error constants
     */
    const DOES_EXIST = 'fileNotExistsDoesExist';

    /**
     * @var array Error message templates
     */
    protected $_messageTemplates = array(
        self::DOES_EXIST => "File '%value%' exists",
    );


    private $_extension = '';

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if the file does not exist in the set destinations
     *
     * @param  string  $value Real file to check for
     * @param  array   $file  File data from Zend_File_Transfer
     * @return boolean
     */
    public function isValid($value, $file = null)
    {
        $directories = $this->getDirectory(true);
        if (($file !== null) and (!empty($file['destination']))) {
            $directories[] = $file['destination'];
        } else if (!isset($file['name'])) {
            $file['name'] = $value;
        }


        if(isset($file['tmp_name'])){
            $name = Base::getFriendlyUri(Base::getFileNameWithoutExt($file['name']));
            $ext = Base::getFileExt($file['name']);
            $file['name'] = $name.'.'.$ext;
        }else{
            $file['name'] = Base::getFriendlyUri($file['name']).$this->_extension;
        }


        foreach ($directories as $directory) {
            if (empty($directory)) {
                continue;
            }

            $check = true;
            if (file_exists($directory . DIRECTORY_SEPARATOR . $file['name'])) {
                return $this->_throw($file, self::DOES_EXIST);
            }
        }

        if (!isset($check)) {
            return $this->_throw($file, self::DOES_EXIST);
        }

        return true;
    }


    public function setExtension($extension)
    {
        $this->_extension = $extension;

        return $this;
    }

}
