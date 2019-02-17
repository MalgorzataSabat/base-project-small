<?php

class Base_Tool_Project_Provider_Colors extends Zend_Tool_Project_Provider_Abstract
{
	static $msg_counter = 0;
	
	/**
	 * @var Zend_Application
	 */
	private $app;
	
	public function __construct(){
		//parent::__construct();
		$this->app = Zend_Registry::get('application');
	}
	
	public function test() {
		
		$this->_registry->getResponse()->appendContent('Black:');
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>array('black', 'bgWhite')));
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>array('hiBlack', 'bgWhite')));
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>'bgBack'));
		$this->_registry->getResponse()->appendContent('');
		
		$this->_registry->getResponse()->appendContent('Red:');
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>'red'));
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>'hiRed'));
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>'bgRed'));
		$this->_registry->getResponse()->appendContent('');
		
		$this->_registry->getResponse()->appendContent('Green:');
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>'green'));
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>'hiGreen'));
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>array('bgGreen', 'black')));
		$this->_registry->getResponse()->appendContent('');
		
		$this->_registry->getResponse()->appendContent('Yellow:');
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>'yellow'));
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>'hiYellow'));
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>array('bgYellow', 'black')));
		$this->_registry->getResponse()->appendContent('');
		
		$this->_registry->getResponse()->appendContent('Blue:');
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>'blue'));
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>'hiBlue'));
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>array('bgBlue', '')));
		$this->_registry->getResponse()->appendContent('');
		
		$this->_registry->getResponse()->appendContent('Magenta:');
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>'magenta'));
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>'hiMagenta'));
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>'bgMagenta'));
		$this->_registry->getResponse()->appendContent('');
		
		$this->_registry->getResponse()->appendContent('Cyan:');
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>'cyan'));
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>'hiCyan'));
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>array('bgCyan', 'black')));
		$this->_registry->getResponse()->appendContent('');
		
		$this->_registry->getResponse()->appendContent('White:');
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>'white'));
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>'hiWhite'));
		$this->_registry->getResponse()->appendContent('testtesttesttesttesttesttesttest', array('color'=>array('bgWhite', 'black')));
		$this->_registry->getResponse()->appendContent('');
		
	}
	
	
	private function _print($message, $status) {
		$message = str_pad($message, 60, ' ', STR_PAD_RIGHT);
		$message .= ':   ';
		$this->_registry->getResponse()->appendContent('  '.++self::$msg_counter.') ', array('separator' => false, 'color'=>'hiWhite'));
		$this->_registry->getResponse()->appendContent($message, array('separator' => false, 'color'=>'hiYellow'));
		$this->_registry->getResponse()->appendContent($status, array('color'=>'hiGreen'));
	}
}

