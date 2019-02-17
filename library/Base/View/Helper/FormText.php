<?php

/**
 * Created by PhpStorm.
 * User: Dawid Przygodzki
 * Date: 2015-07-10
 * Time: 11:56
 */
class Base_View_Helper_FormText extends Zend_View_Helper_FormText
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
    public function formText($name, $value = null, $attribs = null)
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

        $xhtml = '<input type="' . $type . '"'
            . ' name="' . $this->view->escape($name) . '"'
            . ' id="' . $this->view->escape($id) . '"'
            . ' value="' . $this->view->escape($value) . '"'
            . $disabled
            . $this->_htmlAttribs($attribs)
            . $this->getClosingBracket();

        return $xhtml;
    }
}