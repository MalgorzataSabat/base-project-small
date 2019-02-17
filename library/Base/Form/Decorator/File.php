<?php
/**
 * Created by PhpStorm.
 * User: RisenetKomp
 * Date: 2014-11-12
 * Time: 12:56
 */

class Base_Form_Decorator_File
    extends Zend_Form_Decorator_File
    implements Zend_Form_Decorator_Marker_File_Interface{

    public function getAttribs()
    {
        $attribs = parent::getAttribs();
        if(!isset($attribs['id']))
            $attribs['id'] = $this->getElement()->getId();

        $filestyle = false;
        if ( array_key_exists('filestyle', $attribs) && $attribs['filestyle'] != false) {
            $filestyle = true;

            if(isset($attribs['filestyle']['buttonText']))
            {
                $attribs['filestyle']['buttonText'] = '&nbsp;&nbsp;'.$attribs['filestyle']['buttonText'];
            }

            $filestyle_option = json_encode((array) $attribs['filestyle']);
            unset($attribs['filestyle']);
        }

        if ( $filestyle ) {
            $view = $this->getElement()->getView();
            $view->headScript()->appendFile('/assets/lib/bootstrap-filestyle/bootstrap-filestyle.js');
            $view->headScript()->appendScript("$('#".$attribs['id']."').filestyle(".$filestyle_option.")");
        }

        return $attribs;
    }

} 