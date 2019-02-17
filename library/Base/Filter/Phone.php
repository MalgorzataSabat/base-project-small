<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.12.2017
 * Time: 16:42
 */


/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

class Base_Filter_Phone implements Zend_Filter_Interface
{


    public function filter($value)
    {
        if(empty($value)){

            return $value;
        }

        return preg_replace('~\D~', '', $value);
    }
}