<?php

class ErrorController extends Base_Controller_Action
{

    private $_error;

    public function init()
    {
        if(Base_Auth::isIdentity())
        {

            Base_Layout::setLayoutByType('default');
        } else {
            Base_Layout::setLayoutByType('empty');
        }

    }

    public function errorAction()
    {
        $this->_error = $this->_getParam('error_handler');
        if (@DEBUG === true) {
            $this->develHandler();
        } else {
            $this->productionHandler();
        }
    }

    /**
     * Error 404 action
     * @throws Exception
     */
    public function error404Action()
    {
        $this->error404();
    }

    /**
     * Error 403 action
     * @throws Exception
     */
    public function error403Action()
    {
        $this->error403();
    }

    /**
     * Error 503 action
     * @throws Exception
     */
    public function error503Action()
    {
        $this->error503();
    }

    private function develHandler()
    {
        if(!$this->view){
            Zend_Debug::dump($this->_error);
        }else{
            $this->view->layout()->disableLayout();

            $this->view->exception = $this->_error->exception;
            $this->view->request = $this->_error->request;
            $this->view->show_trace = true;
            $this->view->show_extended_trace = false;
            $this->view->last_30_lines = true;
        }
    }

    private function productionHandler()
    {
        $exception = $this->_error['exception'];
        if ($exception->getMessage() !== 'Current route is not defined'
            && $exception->getCode() !== 404
            && $exception->getCode() !== 403)
        {
            $this->error503();
        } elseif($exception->getCode() == 403) {
            $this->error403();
        } else {
            $this->error404();
        }
    }

    private function error403()
    {
        $this->_helper->viewRenderer('error403');
        $this->_response->setRawHeader('HTTP/1.1 403 Forbidden');
    }

    private function error404()
    {
        $this->_helper->viewRenderer('error404');
        $this->_response->setRawHeader('HTTP/1.1 404 Not Found');
    }

    private function error503()
    {
        $this->_response->setRawHeader('HTTP/1.1 503 Service Unavailable');
        $this->_helper->viewRenderer('error503');

        $this->_logError();
    }


    /**
     * Shutdown function
     * write error to file
     */
    private function _logError()
    {
        if(!$this->_error) return;

        $error_file = APPLICATION_DATA . '/logs/log_apps.log';
        // set content of error file
        $content = 'Date: ' . date('Y-m-d H:i:s') . PHP_EOL;
        $content .= 'Message: ' . $this->_error->exception->getMessage() . PHP_EOL;
        $content .= 'File: ' . $this->_error->exception->getFile() . PHP_EOL;
        $content .= 'Line: ' . $this->_error->exception->getLine() . PHP_EOL;
        $content .= 'Request Uri: ' . $this->_error->request->getRequestUri() . PHP_EOL;
        $content .= 'Request Method: ' . $this->_error->request->getMethod() . PHP_EOL;
        $content .= 'Request Params: ' . json_encode($this->_error->request->getParams()) . PHP_EOL;
        $content .= 'Id User: ' . Base_Auth::getUser('id_user') . PHP_EOL;
        $content .= '-----------------------------------' . PHP_EOL;

        // write content to file
        if(file_exists($error_file)){
            $content.= file_get_contents($error_file, false, null, null, 1024*1024); // last 1Mb of log;
        }

        $handle = fopen( $error_file, 'w' );
        fwrite($handle, $content);
        fclose($handle);
    }


}