<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.12.2017
 * Time: 16:43
 */

class Base_View_Helper_DisplayPhone extends Zend_View_Helper_Abstract
{
    private $areaCode = array( 12, 13,  14,  15,  16,  17,  18,  22,  23,  24,  25,  29,  32,  33,  34,  41,  42,  43,  44,  46,  48,  52,  54,  55,  56,  58,  59,  61,  62,  63,  65,  67,  68,  71,  74,  75,  76,  77,  81,  82,  83,  84,  85,  86,  87,  89 , 91,  94,  95);

    public function displayPhone($phone)
    {
        $nacional = null;
        $phone = preg_replace('~\D~', '',$phone);

        if(($length = strlen($phone)) > 9)
        {
            $len = $length -9;
            $nacional = '+'.substr($phone, 0,$len);
            $phone = substr($phone, $len,9);
        }

        if(in_array(substr($phone, 0, 2), $this->areaCode))
        {
            $phone = $this->getHomePhone($phone, $nacional);
        } else {
            $phone = $this->getMobilePhone($phone, $nacional);
        }

        $result = isset($nacional) ? $nacional.' '.$phone : $phone ;


        return $result;
    }

    private function getHomePhone($phone, $nacional = null)
    {
        $phone = preg_replace('/^([0-9]{2})([0-9]{3})([0-9]{2})([0-9]{2})/','($1) $2-$3-$4',$phone);
        return $phone;
    }

    private function getMobilePhone($phone, $nacional = null)
    {
        $phone = preg_replace('/^([0-9]{3})([0-9]{3})([0-9]{3})/', '$1-$2-$3',$phone);
        return $phone;
    }

}