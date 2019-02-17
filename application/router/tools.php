<?php
$router = Zend_Controller_Front::getInstance()->getRouter();

$router->addRoute( 'tools_gus', new Zend_Controller_Router_Route(
    '/gus',
    array(
        'module' => 'default',
        'controller' => 'tools',
        'action' => 'gus',
    )
) );

$router->addRoute( 'tools_search', new Zend_Controller_Router_Route(
    '/search',
    array(
        'module' => 'default',
        'controller' => 'tools',
        'action' => 'search',
    )
) );

$router->addRoute( 'tools_cookie', new Zend_Controller_Router_Route(
    '/cookie',
    array(
        'module' => 'default',
        'controller' => 'tools',
        'action' => 'cookie',
    )
) );

$router->addRoute( 'tools_get-file', new Zend_Controller_Router_Route(
    '/get-file/:file',
    array(
        'module' => 'default',
        'controller' => 'tools',
        'action' => 'get-file',
    ),
    array(
        'file' => '.*'
    )
) );

Zend_Controller_Front::getInstance()->setRouter($router);
