<?php

$router = Zend_Controller_Front::getInstance()->getRouter();

$router->addRoute('user', new Zend_Controller_Router_Route(
	'/@users',
	array(
		'module' => 'user',
		'controller' => 'index',
		'action' => 'index',
	)
));


Zend_Controller_Front::getInstance()->setRouter($router);


