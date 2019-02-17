<?php
$router = Zend_Controller_Front::getInstance()->getRouter();

$router->addRoute( 'error_404', new Zend_Controller_Router_Route(
    '/404.html',
    array(
        'module' => 'default',
        'controller' => 'error',
        'action' => 'error404',
    )
) );

$router->addRoute( 'error_503', new Zend_Controller_Router_Route(
    '/503.html',
    array(
        'module' => 'default',
        'controller' => 'error',
        'action' => 'error503',
    )
) );

Zend_Controller_Front::getInstance()->setRouter($router);
