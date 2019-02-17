<?php
/**
 * Created by PhpStorm.
 * User: Dawid Przygodzki
 * Date: 2015-05-07
 * Time: 14:45
 */

class Base_Validate_File_MaxPostSize extends Zend_Validate_File_Size
{
    /**
     * @const string Error constants
     */
    const TOO_BIG = 'fileFilesSizeTooBig';

    /**
     * @var array Error message templates
     */
    protected $_messageTemplates = array(
        self::TOO_BIG => "Max PHP value [post_max_size] or [upload_max_filesize] is '%max%' but '%size%' were detected",
    );

    public function isValid($value, $file = null)
    {
        if( $file['size'] > $this->_getMaximumFileUploadSize() )
        {
            $this->_max = $this->_toByteString($this->_getMaximumFileUploadSize());
            $this->_size = $this->_toByteString($file['size']);
            $this->_throw($file, self::TOO_BIG);
        }

        if (count($this->_messages) > 0) {
            return false;
        }

        return true;
    }

    private function _convertPHPSizeToBytes($sSize)
    {
        if ( is_numeric( $sSize) ) {
            return $sSize;
        }
        $sSuffix = substr($sSize, -1);
        $iValue = substr($sSize, 0, -1);
        switch(strtoupper($sSuffix)){
            case 'P':
                $iValue *= 1024;
            case 'T':
                $iValue *= 1024;
            case 'G':
                $iValue *= 1024;
            case 'M':
                $iValue *= 1024;
            case 'K':
                $iValue *= 1024;
                break;
        }
        return $iValue;
    }

    private function _getMaximumFileUploadSize()
    {
        return min($this->_convertPHPSizeToBytes(ini_get('post_max_size')), $this->_convertPHPSizeToBytes(ini_get('upload_max_filesize')));
    }
} 