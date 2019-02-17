<?php

/**
 * Created by PhpStorm.
 * User: Robert Rogiński
 * Date: 2016-12-05
 * Time: 11:53
 */
class Base_View_Helper_FormSearchNumber extends Zend_View_Helper_FormText
{
    /**
     * Input type array
     *
     * @var array
     */
    private $_inputType = array(
        'button', 'checkbox', 'color', 'date', 'datetime', 'datetime-local', 'email', 'file', 'hidden', 'image', 'month',
        'number', 'password', 'radio', 'range', 'reset', 'search', 'submit', 'tel','text', 'time', 'url', 'week',
    );



    /**
     * Generates a 'text' element.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are used in place of added parameters.
     *
     * @param mixed $value The element value.
     *
     * @param array $attribs Attributes for the element tag.
     *
     * @return string The element XHTML.
     */
    public function formSearchNumber($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        // build the element
        $disabled = '';
        if ($disable) {
            // disabled
            $disabled = ' disabled="disabled"';
        }

        // type of input
        $type = isset($attribs['type']) ? $attribs['type'] : '';
        unset($attribs['type']);

        if(!in_array($type, $this->_inputType)){
            $type = 'text';
        }


        $xhtml = '';

        $value_min = isset($value['min']) ? $value['min'] : '';
        $value_max = isset($value['max']) ? $value['max'] : '';

        $xhtml.= '<div class="row">';
        $xhtml.= $this->view->formHidden($name.'[type]', 'range');
        $xhtml.= '<div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">'.$this->view->translate('label_from').'</span>
                    ';
                    $xhtml.= '<input type="' . $type . '"'
                        . ' name="' . $this->view->escape($name) . '[min]"'
                        . ' id="' . $this->view->escape($id) . '-min"'
                        . ' value="' . $this->view->escape($value_min) . '"'
                        . $disabled
                        . $this->_htmlAttribs($attribs)
                        . $this->getClosingBracket();
            $xhtml.= '</div></div>';

            $xhtml.= '<div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">'.$this->view->translate('label_to').'</span>
                    ';
            $xhtml.= '<input type="' . $type . '"'
                . ' name="' . $this->view->escape($name) . '[max]"'
                . ' id="' . $this->view->escape($id) . '-max"'
                . ' value="' . $this->view->escape($value_max) . '"'
                . $disabled
                . $this->_htmlAttribs($attribs)
                . $this->getClosingBracket();
            $xhtml.= '</div></div>';
        $xhtml.= '</div>';


        return $xhtml;
    }
}