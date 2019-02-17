<?php

class Auth_IndexController extends Base_Controller_Action
{
    /**
     * @var Auth_Form_Login 
     */
    private $_authFormLogin;
    
    private $_redirectUrl;

    /**
     * @var User
     */
    private $_user;


    /**
     * @var User
     */
    public     $_model = null;

    public function preDispatch()
    {
        parent::preDispatch();

            Base_Layout::setLayoutByType('auth');
            $this->_helper->viewRenderer('index-admin');

    }

    public function indexAction()
    {
        $this->view->pageMainTitle ='Zarejestruj nowego użytkownika';

        $this->_model = new User();

        $this->_model->role = null;


        $this->_authFormLogin = new User_Form_User(array('model' => $this->_model));

        $this->view->title_index_registration_user = 'Formularz rejestracji';


        if($this->_request->isPost() &&
            $this->hasParam($this->_authFormLogin->getElementsBelongTo()) &&
            $this->_authFormLogin->isValid($this->_request->getPost())
        ){
            $this->_model->save();

            $this->_sendMailToUser();


            $this->_flash()->success->addMessage('Zapis wykonany pomyślnie');
            $this->_redirector()->gotoRouteAndExit(array(), 'auth_index');
        }


        $this->view->authFormLogin = $this->_authFormLogin;
	}


    private function _sendMailToUser()
    {
        $userRole = AclRole::getNameRoleById(intval($this->_model->role));

        if($userRole->name_role == 'tester'){
            $this->view->rowsForm = array('Systemy testujące' => 'testing_systems', 'Systemy raportowe' => 'reporting_systems', 'Zna selenium' => 'knowledge_of_selenium');
        }
        elseif($userRole->name_role == 'developer'){
            $this->view->rowsForm = array('Środowiska ide' => 'ide_environment', 'Języki programowania' => 'programming_languages', 'Zna mysql' => 'knowledge_of_mysql');
        }elseif($userRole->name_role == 'project-manager'){
            $this->view->rowsForm = array('Metodologie dot. projektów' => 'pm_methodologies', 'Systemy raportowe' => 'pm_reports_systems', 'Zna scrum' => 'knowledge_of_scrum');
        }
        $this->view->boolArray = array('knowledge_of_selenium','knowledge_of_mysql','knowledge_of_scrum');
        $this->view->userRole = AclRole::getFullNameRoleById(intval($this->_model->role));
        $this->view->user = $this->_model;


        $mail = new Base_Mail();
        $mail->setBodyTemplate('index/_mail_register.phtml');
        $mail->setSubject('Potwierdzenie rejestracji');
        $mail->clearRecipients();

        $mail->addTo( $this->_model->getEmail(), $this->_model->getUserName() );
        $mail->send();
    }

    public function listAction()
    {
        $this->view->pageMainTitle ='Lista zarejestrowanych użytkowników';
        $this->_helper->viewRenderer('index-user');
        $this->_formFilter = new User_Form_Filter();
        $this->_dataQuery = $this->_formFilter->getValuesQuery(true);


        $this->_dataQuery['fullname'] = '';

        $query = User::getQuery($this->_dataQuery);


        $this->view->title_index_registration_user = 'Lista użytkowników';

        $this->view->userList = $this->_helper->paging($query, array('limit' => 25));
        $this->view->formFilter = $this->_formFilter;

    }


    public function editAction()
    {

        $this->view->pageMainTitle ='Formularz edycji użytkownika';
        $data['fullname'] = '';

        $this->_model = User::findRecord($this->getParam('id_user'), $data);
        $this->forward403Unless($this->_model);

        $this->_formUser();


        $this->_helper->viewRenderer('form');
        $this->view->model = $this->_model;
    }

    private function _formUser()
    {
        $this->_formUser = new User_Form_User(array('model' => $this->_model));
        $this->view->formUser = $this->_formUser;

        if($this->_request->isPost() &&
            $this->hasParam($this->_formUser->getElementsBelongTo()) &&
            $this->_formUser->isValid($this->_request->getPost())
        ){
            $this->_model->save();

            $this->_flash()->success->addMessage('Zapis wykonany pomyślnie');
            $this->_redirector()->gotoRouteAndExit(array(), 'auth_index_list');
        }
    }

    public function deleteAction()
    {
        $id_user = $this->_getParam('id_user');
        $this->_model = User::findRecord($id_user);
        $this->forward403Unless($this->_model);

        $this->_model->delete();

        $this->_flash()->success->addMessage('Użytkownik został usunięty!');
        $this->_redirector()->gotoRouteAndExit(array(), 'auth_index_list');
    }

    public function archiveAction()
    {
        $this->_model = User::findRecord($this->_getParam('id_user'));
        $this->forward403Unless($this->_model);

        $this->_model->archived_at = date("Y-m-d H:i:s");
        $this->_model->save();

        $this->_flash()->success->addMessage('Przeniesiono do archiwum');
        $this->_redirector()->gotoRouteAndExit(array(), 'auth_index_list');
    }

    
}
