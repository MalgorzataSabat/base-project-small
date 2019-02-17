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
 * @version    $Id: Navigation.php 25024 2012-07-30 15:08:15Z rob $
 */

/**
 * @see Zend_View_Helper_Navigation
 */
require_once 'Zend/View/Helper/Navigation.php';

class Base_View_Helper_Navigation extends Zend_View_Helper_Navigation
{
    /**
     * View helper namespace
     *
     * @var string
     */
    const NS = 'Base_View_Helper_Navigation';


    public function findHelper($proxy, $strict = true)
    {
        if (isset($this->_helpers[$proxy])) {
            return $this->_helpers[$proxy];
        }

        if (!$this->view->getPluginLoader('helper')->getPaths(self::NS)) {
            // Add navigation helper path at the beginning
            $paths = $this->view->getHelperPaths();
            $this->view->setHelperPath(null);

            $this->view->addHelperPath(
                    str_replace('_', '/', self::NS),
                    self::NS);

            foreach ($paths as $ns => $path) {
                $this->view->addHelperPath($path, $ns);
            }
        }

        if ($strict) {
            $helper = $this->view->getHelper($proxy);
        } else {
            try {
                $helper = $this->view->getHelper($proxy);
            } catch (Zend_Loader_PluginLoader_Exception $e) {
                return null;
            }
        }

        if (!$helper instanceof Zend_View_Helper_Navigation_Helper) {
            if ($strict) {
                require_once 'Zend/View/Exception.php';
                $e = new Zend_View_Exception(sprintf(
                        'Proxy helper "%s" is not an instance of ' .
                        'Zend_View_Helper_Navigation_Helper',
                        get_class($helper)));
                $e->setView($this->view);
                throw $e;
            }

            return null;
        }

        $this->_inject($helper);
        $this->_helpers[$proxy] = $helper;

        return $helper;
    }


}
