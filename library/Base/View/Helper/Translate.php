<?php
/** Zend_View_Helper_Translate.php */
require_once 'Zend/View/Helper/Translate.php';

class Base_View_Helper_Translate extends Zend_View_Helper_Translate
{

    public function translate($messageid = null)
    {
        $translate = parent::translate(func_get_args());

//        if(DEBUG){
//            $translate = $translate.' <span title="'.$translate.'">[?]</span>';
//        }

        return $translate;
    }

}

