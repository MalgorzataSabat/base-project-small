<?php

/**
 * @see Zend_Form_Decorator_Abstract
 */
require_once 'Zend/Form/Decorator/Abstract.php';

/**
 * Page_Form_Decorator_Widget
 *
 * Wraps content in an HTML block tag.
 *
 * Options accepted are:
 * - tag: tag to use in decorator
 * - noAttribs: do not render attributes in the opening tag
 * - placement: 'append' or 'prepend'. If 'append', renders opening and
 *   closing tag after content; if prepend, renders opening and closing tag
 *   before content.
 * - openOnly: render opening tag only
 * - closeOnly: render closing tag only
 *
 * Any other options passed are processed as HTML attributes of the tag.
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage Decorator
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: HtmlTag.php 24593 2012-01-05 20:35:02Z matthew $
 */
class Layout_Form_Decorator_Widget extends Zend_Form_Decorator_HtmlTag
{


    /**
     * Render content wrapped in an HTML tag
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        $i = rand(1, 10000);
        $element = $this->getElement();
        $view = $element->getView();
        $name = $element->getAttrib('name');
        $html = '';

        $html.= '<div class="widget-box">
            <div class="widget-title">
                <span class="widget-action widget-move"><i class="fa fa-arrows txt-color-blue"></i></span>
                <h5 data-toggle="collapse" data-target="#demo_'.$i.'">' . $view->translate($name) . '</h5>
                <a href="#"
                   class="widget-action widget-delete deleteWidget"
                   data-overbox-title="'.$view->translate('overbox_confirm_widget-delete_title').'"
                    ><i class="fa fa-remove txt-color-red"></i></a>
            </div>
            <div class="widget-content collapse out" id="demo_'.$i.'">
                '.$content.'
            </div>
        </div>';


        return $html;
    }

}
