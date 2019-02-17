<?php
class Base_View_Helper_FormClear extends Zend_View_Helper_FormSubmit
{
    
    public function formClear($name, $value = null, $attribs = null)
    {
       $info = $this->_getInfo($name, $value, $attribs);
       extract($info);

        if(!isset($attribs['class']) || empty($attribs['class'])){
            $attribs['class'] = 'btn btn-link';
        }

        if(!isset($attribs['href']) || empty($attribs['href'])){
            $attribs['href'] = '?clear=1';
        }

        // build the element
        $xhtml = '<a '
            . $this->_htmlAttribs($attribs)
            . '>'
            . $this->view->escape($value)
            . '</a>';

        return $xhtml;
    }
}