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
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Menu.php 25113 2012-11-07 21:27:34Z rob $
 */

/**
 * @see Zend_View_Helper_Navigation_HelperAbstract
 */
require_once 'Zend/View/Helper/Navigation/Menu.php';

/**
 * Helper for rendering menus from navigation containers
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Base_View_Helper_Navigation_AsideNav
    extends Zend_View_Helper_Navigation_Menu
{

    /**
     * View helper entry point:
     * Retrieves helper and optionally sets container to operate on
     *
     * @param  Zend_Navigation_Container $container  [optional] container to
     *                                               operate on
     * @return Zend_View_Helper_Navigation_Menu      fluent interface,
     *                                               returns self
     */
    public function asideNav(Zend_Navigation_Container $container = null)
    {
        if (null !== $container) {
            $this->setContainer($container);
        }

        return $this;
    }


    /**
     * Returns an HTML string containing an 'a' element for the given page if
     * the page's href is not empty, and a 'span' element if it is empty
     *
     * Overrides {@link Zend_View_Helper_Navigation_Abstract::htmlify()}.
     *
     * @param  Zend_Navigation_Page $page  page to generate HTML for
     * @return string                      HTML string for the given page
     */
    public function htmlify(Zend_Navigation_Page $page)
    {
        // get label and title for translating
        $label = $page->getLabel();
        $title = $page->getTitle();

        // translate label and title?
        if ($this->getUseTranslator() && $t = $this->getTranslator()) {
            if (is_string($label) && !empty($label)) {
                $label = $t->translate($label);
            }
            if (is_string($title) && !empty($title)) {
                $title = $t->translate($title);
            }
        }

        // get attribs for element
        $attribs = array(
            'id'     => $page->getId(),
            'title'  => $title,
        );

        if (false === $this->getAddPageClassToLi()) {
            $attribs['class'] = $page->getClass();
        }

        // does page have a href?
        if ($href = $page->getHref()) {
            $element              = 'a';
            $attribs['href']      = $href;
            $attribs['target']    = $page->getTarget();
            $attribs['accesskey'] = $page->getAccessKey();
        } else {
            $element = 'span';
        }

        // Add custom HTML attributes
        $attribs = array_merge($attribs, $page->getCustomHtmlAttribs());

        $icon = '';
        if ($page->icon) {
            $icon = '<i class="fa fa-lg fa-fw fa-' . $page->icon . ' '. $page->icon_class . '"></i> ';
        }

        $label = $this->view->escape($label);
        if(strlen($icon)){
            $label = '<span class="menu-item-parent">'.$label.'</span>';
        }

        return '<' . $element . $this->_htmlAttribs($attribs) . '>'
             . $icon. $label
             . '</' . $element . '>';
    }



}
