<?php


class Base_View_Helper_FormSelect extends Zend_View_Helper_FormSelect {

    public function formSelect($name, $value = null, $attribs = null,
                               $options = null, $listsep = "<br />\n")
    {

        $select2 = false;
        if ( array_key_exists('select2', $attribs) ) {
//            $select2 = true;
            $attribs['class'] = 'select2 '.str_replace('form-control', '', $attribs['class']);
//            $select2_options = json_encode((array) $attribs['select2']);
            unset($attribs['select2']);
        }
//
        $multiselectOrder = false;
        if ( array_key_exists('multiselectOrder', $attribs) ) {
            $multiselectOrder = true;
        }

        $html = parent::formSelect($name, $value, $attribs, $options, $listsep);
//
        $info = $this->_getInfo($name, $value, $attribs, $options, $listsep);
        extract($info); // name, id, value, attribs, options, listsep, disable
//
//        if ( $select2 ) {
//            $this->view->headLink()->appendStylesheet( '/assets/lib/select2-4.0.0/css/select2.min.css' );
//            $this->view->headScript()->appendFile('/assets/lib/select2-4.0.0/js/select2.min.js');
//            $this->view->headScript()->appendFile('/assets/lib/select2-4.0.0/js/i18n/pl.js');
//            $this->view->headScript()->appendScript("$('#".$id."').select2(".$select2_options.")");
//        }
//
        if($multiselectOrder){
            $this->view->headScript()->appendFile('/assets/js/multiselectOrder.js');
            $this->view->headScript()->appendScript("$('#".$id."').multiselectOrder();");
        }
//
        return $html;
    }
}