<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.12.2017
 * Time: 16:43
 */

class Base_View_Helper_DisplayNip extends Zend_View_Helper_Abstract
{

    public function displayNip($nip)
    {
        $result = preg_replace('/^([0-9]{3})([0-9]{3})([0-9]{2})([0-9]{2})/','$1-$2-$3-$4',$nip);
        return $result;
    }
}