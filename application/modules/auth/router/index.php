<?php
$router = Zend_Controller_Front::getInstance()->getRouter();


$router->addRoute('auth_index', new Zend_Controller_Router_Route(
	'/',
	array(
		'module' => 'auth',
		'controller' => 'index',
		'action' => 'index',
	)
));



$router->addRoute('auth_index_list', new Zend_Controller_Router_Route(
    '/admin',
    array(
        'module' => 'auth',
        'controller' => 'index',
        'action' => 'list'
    )
));


$router->addRoute('auth_index_edit', new Zend_Controller_Router_Route(
    '/admin/edytuj/:id_user',
    array(
        'module' => 'auth',
        'controller' => 'index',
        'action' => 'edit',
    ),
    array(
        'id_user' => '\d+'
    )
));


$router->addRoute('auth_index_delete', new Zend_Controller_Router_Route(
    '/admin/usun/:id_user',
    array(
        'module' => 'auth',
        'controller' => 'index',
        'action' => 'delete',
    ),
    array(
        'id_user' => '\d+'
    )
));


$router->addRoute('auth_index_archive', new Zend_Controller_Router_Route(
    '/admin/archiwizuj/:id_user',
    array(
        'module' => 'auth',
        'controller' => 'index',
        'action' => 'archive'
    ),
    array(
        'id_user' => '\d+'
    )
));

Zend_Controller_Front::getInstance()->setRouter($router);


