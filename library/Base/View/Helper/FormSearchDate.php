<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Robert Rogiński
 * Date: 05.12.2016
 * Time: 14:29
 * To change this template use File | Settings | File Templates.
 */

class Base_View_Helper_FormSearchDate extends Zend_View_Helper_FormElement {

    public function formSearchDate($name, $value=null, $attribs=null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        // build the element
        $disabled = '';
        if ($disable) {
            // disabled
            $disabled = ' disabled="disabled"';
        }

        $attribs['class'] = trim(@$attribs['class']);


        // end tag depend of doctype
        $endTag = ' />';

        if ( ($this->view instanceof Zend_View_Abstract) &&  !$this->_isXhtml()) {
            $endTag = '>';
        }

//        $addon_icon = isset($attribs['addon-icon']) && strlen($attribs['addon-icon']) > 0 ? $attribs['addon-icon'] : 'calendar';

//        $xhtml = '
//        <div class="input-group date form_time '.$this->view->escape($id).'">
//            <input type="text" id="'.$this->view->escape($id).'" name="'.$this->view->escape($name).'" value="'.$this->view->escape($value).'" ' . $disabled . $this->_htmlAttribs($attribs) . $endTag
//            .' <span class="input-group-addon"><span class="fa fa-' . $addon_icon . '"></span></span>
//        </div>';




        $value_min = isset($value['min']) ? $value['min'] : '';
        $value_max = isset($value['max']) ? $value['max'] : '';
        $addon_icon = isset($attribs['addon-icon']) && strlen($attribs['addon-icon']) > 0 ? $attribs['addon-icon'] : 'calendar';

        $xhtml = '';

        $xhtml.= '<div class="row">';
        $xhtml.= $this->view->formHidden($name.'[type]', 'range');
        $xhtml.= '<div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">'.$this->view->translate('label_from').'</span>
                    ';
        $xhtml.= '<input type="text"'
            . ' name="' . $this->view->escape($name) . '[min]"'
            . ' id="' . $this->view->escape($id) . '-min"'
            . ' value="' . $this->view->escape($value_min) . '"'
            . $disabled
            . $this->_htmlAttribs($attribs)
            . $this->getClosingBracket();
        $xhtml.= '<span class="input-group-addon"><span class="fa fa-' . $addon_icon . '"></span></span>';
        $xhtml.= '</div></div>';


        $xhtml.= '<div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">'.$this->view->translate('label_to').'</span>
                    ';
        $xhtml.= '<input type="text"'
            . ' name="' . $this->view->escape($name) . '[max]"'
            . ' id="' . $this->view->escape($id) . '-max"'
            . ' value="' . $this->view->escape($value_max) . '"'
            . $disabled
            . $this->_htmlAttribs($attribs)
            . $this->getClosingBracket();
        $xhtml.= '<span class="input-group-addon"><span class="fa fa-' . $addon_icon . '"></span></span>';
        $xhtml.= '</div></div>';
        $xhtml.= '</div>';


        $datetime_option = array('language' => 'pl', 'format' => 'YYYY-MM-DD', 'pickTime' => false);

        if ( array_key_exists('datetime', $attribs) ) {
            $datetime_option = $datetime_option + $attribs['datetime'];
            unset($attribs['datetime']);
        }
        $datetime_option = json_encode($datetime_option);

        $this->view->headScript()->appendScript("$('#".$this->view->escape($id)."-min').datetimepicker(".$datetime_option.")");
        $this->view->headScript()->appendScript("$('#".$this->view->escape($id)."-max').datetimepicker(".$datetime_option.")");



        return $xhtml;
    }
}