<?php

class Base_View_Helper_ImageAddMulti extends Zend_View_Helper_Abstract
{

    private $_renderView = null;

    public function imageAddMulti($channel, $object_id = null, $data = array())
    {
        $view = clone $this->view;

        if(null == $this->_renderView){
            $this->_renderView = 'index/add-multi.phtml';

            $path = APPLICATION_PATH.DS.'modules'.DS.'image'.DS.'views';
            $view->addBasePath($path);
        }


        $view->channel = $channel;
        $view->object_id = $object_id;

        return $view->render($this->_renderView);
    }
}