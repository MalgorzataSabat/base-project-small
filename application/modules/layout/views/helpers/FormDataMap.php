<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: FormFile.php 23775 2011-03-01 17:25:24Z ralph $
 */


/**
 * Abstract class for extension
 */
require_once 'Zend/View/Helper/FormElement.php';


/**
 * Helper to generate a "file" element
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Layout_View_Helper_FormDataMap extends Zend_View_Helper_FormElement
{
    /**
     * Generates a 'file' element.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     *
     * @param array $attribs Attributes for the element tag.
     *
     * @return string The element XHTML.
     */
    public function formDataMap($name, $value, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info);
        $xhtml = '';

        /* check if there is any element map, if not create empty array() */
        $value = (array) $value;

        /** @var $view Zend_View */
        $view = clone Base_Layout::getView();
        $layoutTemplate = Base_Layout::getLayoutTemplate($attribs['id_layout_template']);

        $view->headLink()->appendStylesheet('/assets/lib/fancybox/source/jquery.fancybox.css');
        $view->headScript()->appendFile('/assets/lib/fancybox/source/jquery.fancybox.js');
        $view->headScript()->appendScript("$('.add-widget').fancybox({width:'85%', minWidth:'780'});");

        $view->headScript()->appendFile('/assets/lib/tinymce/jquery.tinymce.min.js');
        $view->headScript()->appendFile('/assets/js/jsTinyMce.js');
        $view->headScript()->appendFile('/assets/js/layout-map.js');

        $definitions = json_decode($layoutTemplate['data_map'], true);

        $i = 0;
        foreach($definitions as $k => $v ){
            $view->placeholder($k)->{$k.'_'.($i++)} = $this->view->partial('index/_data_map.phtml', array(
                'map' => (array) @$value[$k],
                'placeholder' => $k,
                'name' => $name,
            ));
        }


        $xhtml.= $view->render($layoutTemplate['filename'].'.phtml');

        return $xhtml;
    }
}
