<?php

class Base_View_Helper_Date extends Zend_View_Helper_Abstract {


    public function date($date, $options = array())
    {
        if(!strlen($date)){
            return '';
        }

        $format = isset($options['format']) ? $options['format'] : 'Y-m-d H:i';

        return date($format, strtotime($date));
    }
}