<?php


/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';


/**
 * @category   Zend
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Base_Filter_HttpLink implements Zend_Filter_Interface
{


    public function filter($value)
    {
        if(empty($value)){
            return $value;
        }

        return (strstr($value, 'https://' ) || strstr($value, 'http://' )) ? $value : 'http://'.$value;
    }
}
