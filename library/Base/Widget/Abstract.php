<?php


class Base_Widget_Abstract
{

    protected $view;
    protected $params = array();

    /** @var Base_Controller_Action */
    public $controler;


    public $name = 'Widget Name';
    protected $_renderView = 'widget.phtml';


    public function getConfig()
    {
        return array('name' => trim($this->name), 'class' => get_called_class());
    }


    public function form($params)
    {
        $this->params = $params;
        $this->view = clone Base_Layout::getView();
        $this->view->form = new Layout_Form_Widget();

        $this->view->form->addElement('hidden', '_class', array('value' => get_called_class()));

        $this->preConfigureForm();
        $this->configureForm();
        $this->postConfigureForm();

        $this->view->form->populate($params);

        $this->view->form->setAttrib('name', trim($this->name));
        $this->view->form->setElementsBelongTo($this->params['_BelongsTo']);

        return $this->view->form;
    }


    public function render($params = array())
    {
        $this->params = $params;
        $this->view = clone Base_Layout::getView();
        $this->view->clearVars();

        $moduleName = $this->getModuleName();
        $this->view->setScriptPath(APPLICATION_PATH."/modules/".$moduleName."/views/widgets");
        $this->view->addScriptPath(APPLICATION_PATH."/views/scripts/");

        $this->preRenderWidget();
        $this->renderWidget();
        $this->postRenderWidget();

        $this->view->params = $this->params;

        if(isset($this->params['renderView'])){
            $this->_renderView = $this->params['renderView'];
        }

        // check whether view is not empty
        if($this->getRenderView()){
            return $this->view->render($this->getRenderView());
        }

        return;
    }

    protected function preConfigureForm(){}
    protected function configureForm(){}
    protected function postConfigureForm(){}

    protected function preRenderWidget(){}
    protected function renderWidget(){}
    protected function postRenderWidget(){}


    public function getViewScriptsDir()
    {
        return $this->view_scripts_dir;
    }

    public function setRenderView($renderView)
    {
        $this->_renderView = $renderView;

        return $this;
    }

    public function getRenderView()
    {
        if(!$this->_renderView){
            return null;
        }

        return trim(str_replace('.phtml', '', $this->_renderView)).'.phtml';
    }


    /**
     * Retrieve module name
     *
     * @return string
     */
    public function getModuleName()
    {
        $class = get_called_class();
        $moduleName = 'default';
        if (preg_match('/^([a-z][a-z0-9]*)_/i', $class, $matches)) {
            $filter = new Zend_Filter_Word_CamelCaseToDash();
            $convert = $filter->filter($matches[1]);
            $moduleName = strtolower($convert);
        }

        return $moduleName;
    }


}
