<?php
$router = Zend_Controller_Front::getInstance()->getRouter();

$router->addRoute('layout_new', new Zend_Controller_Router_Route(
    '/@layout/@new/:type',
    array(
        'module' => 'layout',
        'controller' => 'index',
        'action' => 'new',
        'type' => 'dashboard',
    ),
    array(
        'type' => '.*'
    )
));

$router->addRoute('layout_edit', new Zend_Controller_Router_Route(
    '/@layout/@edit/:id_layout',
    array(
        'module' => 'layout',
        'controller' => 'index',
        'action' => 'edit',
    ),
    array(
        'id_layout' => '\d+'
    )
));

$router->addRoute('layout_delete', new Zend_Controller_Router_Route(
    '/@layout/@delete/:id_layout',
    array(
        'module' => 'layout',
        'controller' => 'index',
        'action' => 'delete',
    ),
    array(
        'id_layout' => '\d+'
    )
));

$router->addRoute('layout_widget-list', new Zend_Controller_Router_Route(
    '/@layout/@widget/@list',
    array(
        'module' => 'layout',
        'controller' => 'index',
        'action' => 'widget-list',
    )
));

$router->addRoute('layout_widget-get', new Zend_Controller_Router_Route(
    '/@layout/@widget/@get/:namespace/:widget',
    array(
        'module' => 'layout',
        'controller' => 'index',
        'action' => 'widget-get',
    ),
    array(
        'namespace' => '.*',
        'widget' => '.*'
    )
));

$router->addRoute('layout_load-template', new Zend_Controller_Router_Route(
    '/@layout/@load-template',
    array(
        'module' => 'layout',
        'controller' => 'index',
        'action' => 'load-template',
    )
));


Zend_Controller_Front::getInstance()->setRouter($router);


