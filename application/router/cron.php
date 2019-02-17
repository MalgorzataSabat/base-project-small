<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Robert Rogiński
 * Date: 12.08.2014
 * Time: 09:07
 * To change this template use File | Settings | File Templates.
 */

$router = Zend_Controller_Front::getInstance()->getRouter();


$router->addRoute('cron', new Zend_Controller_Router_Route(
    '/run-cron',
    array(
        'module' => 'default',
        'controller' => 'cron',
        'action' => 'index',
    )
));



Zend_Controller_Front::getInstance()->setRouter($router);
