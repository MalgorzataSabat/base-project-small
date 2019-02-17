<?php

class Layout_IndexController extends Base_Controller_Action
{

    /**
     * @var Layout
     */
    private $_model = null;


    public function indexAction()
    {

    }

    public function newAction()
    {
        $this->_model = new Layout();
        $this->_model->type = $this->getParam('type');
        $this->_model->id_user = Base_Auth::getUserId();
        $this->_model->LayoutTemplate = LayoutTemplate::getDefaultRecord();

        $this->_formLayout();
    }


    public function editAction()
    {
        $this->_model = Layout::findRecord($this->_getParam('id_layout'));
        $this->forward404Unless($this->_model);

        if($this->_model['id_user'] != Base_Auth::getUserId()){
            $this->forward403();
        }


        $this->_formLayout();
    }


    private function _formLayout()
    {
        $this->_helper->viewRenderer('form');
        $form = new Layout_Form_Layout(array('model' => $this->_model));

        if($this->_request->isPost() && $form->isValid($this->_request->getPost())){
            $this->_model->save();

            if($this->_request->isXmlHttpRequest()){
                $this->_helper->viewRenderer('form-ajax-result');
            }else{
                $this->_flash()->success->addMessage('Zapis wykonany pomyślnie');
                $this->_redirector()->gotoRouteAndExit();
            }
        }

        $this->view->form = $form;
        $this->view->model = $this->_model;
    }


    public function widgetListAction()
    {
        Base_Layout::setLayoutByType('empty');
        $widgetDashboardList = Base_Widget::getWidgetList('all');

        $this->view->list = array();
        if(count($widgetDashboardList) > 0){
            $this->view->list = $widgetDashboardList;
        }
    }


    public function widgetGetAction()
    {
        $widget_name = $this->_getParam('widget');
        $namespace = $this->_getParam('namespace');
        $placeholder = $this->_getParam('placeholder');


        $widgetConfig = Base_Widget::getWidget($widget_name, $namespace);
        $widget = new $widgetConfig();

        $v['_BelongsTo'] = 'data_map['.$placeholder.'][widgets]['.time().']';

        echo $widget->form($v);
        exit();
    }

    public function loadTemplateAction()
    {
        $layoutTemplate = Base_Layout::getLayoutTemplate($this->_getParam('id_layout_template'));
        $placeholders = array();

        $id_layout = $this->getParam('id_layout');
        if($id_layout){
            $this->_model = Layout::find($id_layout);
            if($this->_model){
                $placeholders = (array) json_decode($this->_model['data_map'], true);
            }
        }

        $html = '';
        if (!$layoutTemplate) {
            $html.= '<div class="alert alert-danger">'.$this->view->translate('error_selected-layout_is-not-registred').'</div>';
        } else {
            $html.= $this->view->formDataMap( 'data_map', $placeholders, array(
                'id_layout_template' => $this->_getParam('id_layout_template'),
            ));
        }

        $this->_helper->json( array(
            'result' => true,
            'layout' => $layoutTemplate,
            'html' => $html,
        ));
    }

}
