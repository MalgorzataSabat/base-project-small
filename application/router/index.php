<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Robert Rogiński
 * Date: 12.08.2014
 * Time: 09:07
 * To change this template use File | Settings | File Templates.
 */

$router = Zend_Controller_Front::getInstance()->getRouter();


$router->addRoute('home', new Zend_Controller_Router_Route(
    '/',
    array(
        'module' => 'default',
        'controller' => 'index',
        'action' => 'index',
    )
));

$router->addRoute('admin', new Zend_Controller_Router_Route(
    '/',
    array(
        'module' => 'default',
        'controller' => 'index',
        'action' => 'index',
    )
));


$router->addRoute( 'manifest', new Zend_Controller_Router_Route(
    '/manifest.json',
    array(
        'module' => 'default',
        'controller' => 'index',
        'action' => 'manifest',
    )
) );


Zend_Controller_Front::getInstance()->setRouter($router);
