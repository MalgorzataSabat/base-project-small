<?php
$router = Zend_Controller_Front::getInstance()->getRouter();

$router->addRoute('query', new Zend_Controller_Router_Route(
    '/@query/:form',
    array(
        'module' => 'default',
        'controller' => 'query',
        'action' => 'index',
    ),
    array(
        'form' => '.*',
    )
));


$router->addRoute('query_new', new Zend_Controller_Router_Route(
    '/@query/@new/:form',
    array(
        'module' => 'default',
        'controller' => 'query',
        'action' => 'new',
    ),
    array(
        'form' => '.*',
    )
));

$router->addRoute('query_edit', new Zend_Controller_Router_Route(
    '/@query/@edit/:form/:id_query',
    array(
        'module' => 'default',
        'controller' => 'query',
        'action' => 'edit',
    ),
    array(
        'id_query' => '((\d+)|([0-9a-f]{32}))',
        'form' => '.*',
    )
));

$router->addRoute('query_delete', new Zend_Controller_Router_Route(
    '/@query/@delete/:form/:id_query',
    array(
        'module' => 'default',
        'controller' => 'query',
        'action' => 'delete',
    ),
    array(
        'id_query' => '((\d+)|([0-9a-f]{32}))',
        'form' => '.*',
    )
));

$router->addRoute('query_save-order', new Zend_Controller_Router_Route(
    '/@query/@save-order/:form',
    array(
        'module' => 'default',
        'controller' => 'query',
        'action' => 'save-order',
    ),
    array(
        'form' => '.*',
    )
));

$router->addRoute('query_save', new Zend_Controller_Router_Route(
    '/@query/@save/:form/:id_query',
    array(
        'module' => 'default',
        'controller' => 'query',
        'action' => 'save',
    ),
    array(
        'form' => '.*',
        'id_query' => '((\d+)|([0-9a-f]{32}))',
    )
));


Zend_Controller_Front::getInstance()->setRouter($router);
