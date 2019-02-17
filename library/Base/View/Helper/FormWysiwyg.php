<?php
class Base_View_Helper_FormWysiwyg extends Zend_View_Helper_FormTextarea
{
    
   public function formWysiwyg($name, $value = null, $attribs = null)
   {
       $info = $this->_getInfo($name, $value, $attribs);
       extract($info);
 
       if (empty($value)) {
           $value = '';
       }

       $this->view->headScript()->appendFile('/assets/lib/tinymce/jquery.tinymce.min.js');
       $this->view->headScript()->appendFile('/assets/js/jsTinyMce.js');

       $this->view->headLink()->appendStylesheet('/assets/lib/fancybox/source/jquery.fancybox.css');
       $this->view->headScript()->appendFile('/assets/lib/fancybox/source/jquery.fancybox.js');


       // build the element
       $xhtml = '
       <textarea aria-role="tinymce" name="' . $this->view->escape($name) . '"'
           . ' id="' . $this->view->escape($id) . '"'
           . $this->_htmlAttribs($attribs) . '>'
           . $this->view->escape($value) . '</textarea>';
 
       return $xhtml;
   }
}