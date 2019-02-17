<?php

class Base_Tool_Project_Provider_Abstract extends Zend_Tool_Project_Provider_Abstract
{
    protected $timer = 0;

    /**
     * @var Zend_Application
     */
    protected $app;

    public function __construct()
    {
        $this->app = Zend_Registry::get('application');
        $this->_resetTimer();
    }

    protected function _print($message, $show_timer = true)
    {
        if ($show_timer) {
            $time = $this->__microtime_float() - $this->timer;
            $this->_registry->getResponse()->appendContent(" [" . number_format($time, 2) . " sec] " . $message, array('color' => 'hiGreen'));
            $this->_resetTimer();
        } else {
            $this->_registry->getResponse()->appendContent($message, array('color' => 'hiGreen'));
        }
    }

    protected function _resetTimer()
    {
        $this->timer = $this->__microtime_float();
    }

    protected function __microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}