<?php
class Base_Controller_Action extends Zend_Controller_Action
{


    /**
     * @var Zend_Controller_Request_Http
     */
    protected $_request;

    /**
     * @var Base_Acl
     */
    protected $_acl;

	public function init()
	{
		parent::init();
//        $this->_response->setHeader('Content-type', 'text/html; charset=utf-8', true);
        Base_Layout::setLayoutByType('default');
        Base_I18n::setContentLanguage($this->getParam('setContentLanguage'));
       // $this->_acl = Base_Acl::getInstance();
    }

	public function preDispatch()
	{
		parent::preDispatch();

        if(!$this->_request->isXmlHttpRequest()){
            $this->view->headMeta()->appendName('keywords', ' ');
            $this->view->headMeta()->appendName('description', ' ');
            $this->view->headMeta()->appendName('viewport', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no');
            $this->view->headMeta()->appendHttpEquiv('pragma', 'cache')->appendHttpEquiv('Cache-Control', 'cache');

          ;
	    }
    }

    public function  postDispatch()
    {
        parent::postDispatch();

        if($this->view->layout()->isEnabled() && $this->_request->isXmlHttpRequest()){
            Base_Layout::setLayoutByType('overbox');
        }
    }

	/**
	 * @return Zend_Controller_Action_Helper_Redirector
	 */
	public function _redirector()
	{
		return $this->_helper->redirector;
	}


    public function _gotoReturnUrl($route = 'home', $params = array())
    {
        $returnUrl = $this->_getParam('return', $this->_request->getHeader('referer'));
        $returnUrl = $returnUrl ? $returnUrl : $returnUrl = Base::url($route, $params);
        $this->_redirector()->gotoUrlAndExit($returnUrl);
    }


    /**
     * @return Base_Controller_Action_Helper_FlashMessenger
     */
    public function _flash()
    {
        return $this->_helper->flashMessenger;
    }

    /**
     * Forwards current action to the default 404 error action.
     *
     * @param string $message Message of the generated exception
     * @throws Zend_Controller_Action_Exception
     */
    public function forward404( $message = null )
    {
        throw new Zend_Controller_Action_Exception( $this->_get404Message( $message ), 404 );
    }

    /**
     * Forwards current action to the default 404 error action unless the specified condition is true.
     *
     * @param mixed|bool   $condition A condition that evaluates to true or false
     * @param string $message   Message of the generated exception
     * @throws Zend_Controller_Action_Exception
     */
    public function forward404Unless( $condition, $message = null )
    {
        if ( !$condition ) {
            throw new Zend_Controller_Action_Exception( $this->_get404Message( $message ), 404 );
        }
    }

    /**
     * Forwards current action to the default 404 error action if the specified condition is true.
     *
     * @param bool   $condition A condition that evaluates to true or false
     * @param string $message   Message of the generated exception
     *
     * @throws Zend_Controller_Action_Exception
     */
    public function forward404If( $condition, $message = null )
    {
        if ( $condition ) {
            throw new Zend_Controller_Action_Exception( $this->_get404Message( $message ), 404 );
        }
    }

    /**
     * Redirects current request to a new URL, only if specified condition is true.
     *
     * This method stops the action. So, no code is executed after a call to this method.
     *
     * @param bool   $condition  A condition that evaluates to true or false
     * @param string $url        Url
     * @param string $statusCode Status code (default to 302)
     */
    public function redirectIf( $condition, $url )
    {
        if ( $condition ) {
            $this->redirect( $url );
        }
    }

    /**
     * Redirects current request to a new URL, unless specified condition is true.
     *
     * This method stops the action. So, no code is executed after a call to this method.
     *
     * @param bool   $condition  A condition that evaluates to true or false
     * @param string $url        Url
     * @param string $statusCode Status code (default to 302)
     */
    public function redirectUnless( $condition, $url )
    {
        if ( !$condition ) {
            $this->redirect( $url );
        }
    }

    /**
     * Forwards current action to the default 403 error action.
     *
     * @param string $message Message of the generated exception
     * @throws Zend_Controller_Action_Exception
     */
    public function forward403( $message = null )
    {
        throw new Zend_Controller_Action_Exception( $this->_get403Message( $message ), 403 );
    }

    /**
     * Forwards current action to the default 403 error action unless the specified condition is true.
     *
     * @param mixed|bool   $condition A condition that evaluates to true or false
     * @param string $message   Message of the generated exception
     * @throws Zend_Controller_Action_Exception
     */
    public function forward403Unless( $condition, $message = null )
    {
        if ( !$condition ) {
            throw new Zend_Controller_Action_Exception( $this->_get403Message( $message ), 403 );
        }
    }


    /**
     * Forwards current action to the default 403 error action if the specified condition is true.
     *
     * @param mixed|bool   $condition A condition that evaluates to true or false
     * @param string $message   Message of the generated exception
     * @throws Zend_Controller_Action_Exception
     */
    public function forward403If( $condition, $message = null )
    {
        if ($condition) {
            throw new Zend_Controller_Action_Exception( $this->_get403Message( $message ), 403 );
        }
    }


    protected function _get404Message( $message = null )
    {
        return null === $message ? sprintf(
            'This request has been forwarded to a 404 error page by the action "%s/%s/%s".',
            $this->getRequest()->getModuleName(),
            $this->getRequest()->getControllerName(),
            $this->getRequest()->getActionName()
        ) : $message;
    }


    protected function _get403Message( $message = null )
    {
        return sprintf(
            'Forbidden "%s/%s/%s - %s',
            $this->getRequest()->getModuleName(),
            $this->getRequest()->getControllerName(),
            $this->getRequest()->getActionName(),
            $message);
    }


}

