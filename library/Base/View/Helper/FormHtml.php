<?php
/**
 * Created by PhpStorm.
 * User: Robert Rogiński
 * Date: 30.04.14
 * Time: 12:09
 */
class Base_View_Helper_FormHtml extends Zend_View_Helper_FormElement
{
    /**
     * Generates a 'html' element.
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
    public function formHtml($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        $tag = isset($attribs['tag']) ? $attribs['tag'] : 'p';
        $class = isset($attribs['class']) ? $attribs['class'] : '';
        $value = isset($attribs['html']) ? $attribs['html'] : $value;
        $icon = isset($attribs['icon']) ? '<i class="fa fa-fw fa-'.$attribs['icon'].'"></i>' : '';

        unset($attribs['html']);
        unset($attribs['tag']);

        // build the element
        $xhtml = '<'.$tag.'
            id="' . $this->view->escape($id) . '"
            class="form-control-static '. $class .'" '.$this->_htmlAttribs($attribs).'>'.$icon.$value.'</'.$tag.'>';

        return $xhtml;
    }
}
