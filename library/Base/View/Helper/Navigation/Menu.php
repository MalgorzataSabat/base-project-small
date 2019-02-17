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
require_once 'Zend/View/Helper/Navigation/HelperAbstract.php';

/**
 * Helper for rendering menus from navigation containers
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Base_View_Helper_Navigation_Menu
    extends Zend_View_Helper_Navigation_Menu
{

    /**
     * CSS class to use for the ul element
     *
     * @var string
     */
    protected $_ulClass = 'nav navbar-nav';


    /**
     * Adds CSS class from page to li element
     *
     * @var bool
     */
    protected $_addPageClassToLi = true;


    protected $_user_dropdown = true;

    /**
     * Sets CSS class to use for the first 'ul' element when rendering
     *
     * @param  string $ulClass                   CSS class to set
     * @return Zend_View_Helper_Navigation_Menu  fluent interface, returns self
     */
    public function addUlClass($ulClass)
    {
        if (is_string($ulClass)) {
            $this->_ulClass = trim($this->_ulClass.' '.$ulClass);
        }

        return $this;
    }


    /**
     * @param bool $use_dropdown
     * @return $this
     */
    public function useDropdown($use_dropdown = true)
    {
        $this->_user_dropdown = $use_dropdown;

        return $this;
    }

    /**
     * Renders a normal menu (called from {@link renderMenu()})
     *
     * @param  Zend_Navigation_Container $container     container to render
     * @param  string                    $ulClass       CSS class for first UL
     * @param  string                    $indent        initial indentation
     * @param  string                    $innerIndent   inner indentation
     * @param  int|null                  $minDepth      minimum depth
     * @param  int|null                  $maxDepth      maximum depth
     * @param  bool                      $onlyActive    render only active branch?
     * @param  bool                      $expandSibs    render siblings of active
     *                                                  branch nodes?
     * @param  string|null               $ulId          unique identifier (id)
     *                                                  for first UL
     * @param  bool                      $addPageClassToLi  adds CSS class from
     *                                                      page to li element
     * @param  string|null               $activeClass       CSS class for active
     *                                                      element
     * @param  string                    $parentClass       CSS class for parent
     *                                                      li's
     * @param  bool                      $renderParentClass Render parent class?
     * @return string                                       rendered menu (HTML)
     */
    protected function _renderMenu(Zend_Navigation_Container $container,
                                   $ulClass,
                                   $indent,
                                   $innerIndent,
                                   $minDepth,
                                   $maxDepth,
                                   $onlyActive,
                                   $expandSibs,
                                   $ulId,
                                   $addPageClassToLi,
                                   $activeClass,
                                   $parentClass,
                                   $renderParentClass)
    {
        $html = '';
        // find deepest active
        if ($found = $this->findActive($container, $minDepth, $maxDepth)) {
            $foundPage = $found['page'];
            $foundDepth = $found['depth'];
        } else {
            $foundPage = null;
        }

        // create iterator
        $iterator = new RecursiveIteratorIterator($container,
                            RecursiveIteratorIterator::SELF_FIRST);
        if (is_int($maxDepth)) {
            $iterator->setMaxDepth($maxDepth);
        }

        // iterate container
        $prevDepth = -1;
        foreach ($iterator as $page) {
            $depth = $iterator->getDepth();
            $isActive = $page->isActive(true);
            if ($depth < $minDepth || !$this->accept($page)) {
                // page is below minDepth or not accepted by acl/visibilty
                continue;
            } else if ($expandSibs && $depth > $minDepth) {
            	// page is not active itself, but might be in the active branch
                $accept = false;
                if ($foundPage) {
                    if ($foundPage->hasPage($page)) {
                        // accept if page is a direct child of the active page
                        $accept = true;
                    } else if ($page->getParent()->isActive(true)) {
                        // page is a sibling of the active branch...
                        $accept = true;
                    }
                }
                if (!$isActive && !$accept) {
                    continue;
                }
            } else if ($onlyActive && !$isActive) {
                // page is not active itself, but might be in the active branch
                $accept = false;
                if ($foundPage) {
                    if ($foundPage->hasPage($page)) {
                        // accept if page is a direct child of the active page
                        $accept = true;
                    } else if ($foundPage->getParent()->hasPage($page)) {
                        // page is a sibling of the active page...
                        if (!$foundPage->hasPages() ||
                            is_int($maxDepth) && $foundDepth + 1 > $maxDepth) {
                            // accept if active page has no children, or the
                            // children are too deep to be rendered
                            $accept = true;
                        }
                    }
                }

                if (!$accept) {
                    continue;
                }
            }

            // make sure indentation is correct
            $depth -= $minDepth;
            $myIndent = $indent . str_repeat('        ', $depth);

            if ($depth > $prevDepth) {
                $attribs = array();

                // start new ul tag
                if (0 == $depth) {
                    $attribs = array(
                        'class' => $ulClass,
                        'id'    => $ulId,
                    );
                }elseif($this->_user_dropdown){
                    $attribs = array('class' => 'dropdown-menu');
                }

                // We don't need a prefix for the menu ID (backup)
                $skipValue = $this->_skipPrefixForId;
                $this->skipPrefixForId();

                $html .= $myIndent . '<ul'
                                   . $this->_htmlAttribs($attribs)
                                   . '>'
                                   . self::EOL;

                // Reset prefix for IDs
                $this->_skipPrefixForId = $skipValue;
            } else if ($prevDepth > $depth) {
                // close li/ul tags until we're at current depth
                for ($i = $prevDepth; $i > $depth; $i--) {
                    $ind = $indent . str_repeat('        ', $i);
                    $html .= $ind . '    </li>' . self::EOL;
                    $html .= $ind . '</ul>' . self::EOL;
                }
                // close previous li tag
                $html .= $myIndent . '    </li>' . self::EOL;
            } else {
                // close previous li tag
                $html .= $myIndent . '    </li>' . self::EOL;
            }

            // render li tag and page
            $liClass = '';
            $ddClass = $page->pages && $this->_user_dropdown ? ' dropdown ' : '';

            if ($isActive && $addPageClassToLi) {
                $liClass = $this->_htmlAttribs(
                    array('class' => 'active ' . $ddClass . $page->getClass())
                );
            } else if ($isActive) {
                $liClass = $this->_htmlAttribs(array('class' => $ddClass . 'active'));
            } else if ($addPageClassToLi) {
                $liClass = $this->_htmlAttribs(
                    array('class' => $ddClass . $page->getClass())
                );
            }


            $html .= $myIndent . '    <li ' . $liClass . '>' . self::EOL
                   . $myIndent . '        ' . $this->htmlify($page) . self::EOL;

            // store as previous depth for next iteration
            $prevDepth = $depth;
        }

        if ($html) {
            // done iterating container; close open ul/li tags
            for ($i = $prevDepth+1; $i > 0; $i--) {
                $myIndent = $indent . str_repeat('        ', $i-1);
                $html .= $myIndent . '    </li>' . self::EOL
                       . $myIndent . '</ul>' . self::EOL;
            }
            $html = rtrim($html, self::EOL);
        }

//        exit();
        return $html;
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
        $icon = '';
        $caret = '';

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

        if($page->pages && $this->_user_dropdown){
            $attribs['class'] = isset($attribs['class']) ? $attribs['class'] : ''.' dropdown-toggle';
            $attribs['data-toggle'] = 'dropdown';
            $caret = ' <span class="caret"></span>';
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
        if ($page->icon) {
            $icon = '<span class="glyphicon glyphicon-' . $page->icon .' '. $page->icon_class . '"></span> ';
        } else if ( $page->icon_awesome ) {
            $icon = '<span class="fa fa-' . $page->icon_awesome . ' '. $page->icon_class . '"></span> ';
        }

        $label_pre = $page->label_pre . $icon;

        return '<' . $element . $this->_htmlAttribs($attribs) . '>'
        . $label_pre . $this->view->escape($label) . $caret
        . '</' . $element . '>';
    }

}
