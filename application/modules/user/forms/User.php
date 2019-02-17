<?php

class User_Form_User extends Base_Form_Horizontal
{
        /**
     * @var User
     */
    protected $_model;
    

    public function init()
    {
        $fields = array();
        $fields_additional = array();
        $fields_details_1 = array();
        $fields_details_2 = array();
        $fields_details_3 = array();


        $fields['name'] = $this->createElement( 'text', 'name', array(
            'label' => 'Imię',
            'required' => true,
            'allowEmpty' => false,
            'filters' => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', true)
            ),
            'value' => $this->_model->getName(),
            'size' => 9, 'label-size' => 3,
        ));

        $fields['surname'] = $this->createElement( 'text', 'surname', array(
            'label' => 'Nazwisko',
            'required' => true,
            'allowEmpty' => false,
            'filters' => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', true)
            ),
            'value' => $this->_model->getSurname(),
            'size' => 9, 'label-size' => 3,
        ));



        $fields['email'] = $this->createElement( 'text', 'email', array(
                'label' => 'Email',
                'required' => true,
                'allowEmpty' => false,
                'filters' => array('StringTrim'),
                'value' => $this->_model->getEmail(),
                'validators' => array(
                    array('StringLength', true, array('max' => 255)),
                    array('NotEmpty', true),
                    array('EmailAddress', true)
                ),
                'size' => 9, 'label-size' => 3,
        ));


        $fields['description'] = $this->createElement( 'text', 'description', array(
                'label' => 'Opis',
                'filters' => array('StringTrim'),
                'value' => $this->_model->getDescription(),
                'allowEmpty' => true,
                'required' => false,
                'size' => 9, 'label-size' => 3,
        ));


        $fields_additional['role'] = new User_Form_Element_Role('role', array(
                'label' => 'Stanowisko',
                'value' => $this->_model->getRole(),
                'allowEmpty' => true,
                'required' => false,
                'size' => 9, 'label-size' => 3,
                'decorators' => $this->_elementDecorators,
        ));



        $fields_details_1['testing_systems'] = $this->createElement( 'text', 'testing_systems', array(
            'label' => 'Systemy testujące',
            'filters' => array('StringTrim'),
            'value' => $this->_model->getTestingSystems(),
            'allowEmpty' => true,
            'required' => false,
            'size' => 9, 'label-size' => 3,
            'class' => 'hidden'
        ));

        $fields_details_1['reporting_systems'] = $this->createElement( 'text', 'reporting_systems', array(
            'label' => 'Systemy raportowe',
            'filters' => array('StringTrim'),
            'value' => $this->_model->getReportingSystems(),
            'allowEmpty' => true,
            'required' => false,
            'size' => 9, 'label-size' => 3,
            'class' => 'hidden'
        ));

        $fields_details_1['knowledge_of_selenium'] = $this->createElement( 'checkbox', 'knowledge_of_selenium', array(
            'label' => 'Zna Selenium',
            'required' => false,
            'allowEmpty' => true,
            'size' => 9, 'label-size' => 3,
            'checked' =>  $this->_model->getKnowledgeOfSelenium(),
            'class' => 'hidden'
        ));

        $fields_details_2['ide_environment'] = $this->createElement( 'text', 'ide_environment', array(
            'label' => 'Środowiska IDE',
            'filters' => array('StringTrim'),
            'value' => $this->_model->getIdeEnvironment(),
            'allowEmpty' => true,
            'required' => false,
            'size' => 9, 'label-size' => 3,
            'class' => 'hidden'
        ));

        $fields_details_2['programming_languages'] = $this->createElement( 'text', 'programming_languages', array(
            'label' => 'Języki programowania',
            'filters' => array('StringTrim'),
            'value' => $this->_model->getProgrammingLanguages(),
            'allowEmpty' => true,
            'required' => false,
            'size' => 9, 'label-size' => 3,
            'class' => 'hidden'
        ));

        $fields_details_2['knowledge_of_mysql'] = $this->createElement( 'checkbox', 'knowledge_of_mysql', array(
            'label' => 'Zna Mysql',
            'required' => false,
            'allowEmpty' => true,
            'size' => 9, 'label-size' => 3,
            'checked' =>  $this->_model->getKnowledgeOfMysql(),
            'class' => 'hidden'
        ));


        $fields_details_3['pm_methodologies'] = $this->createElement( 'text', 'pm_methodologies', array(
            'label' => 'Metodologie prowadzenia projektów',
            'filters' => array('StringTrim'),
            'value' => $this->_model->getPmMethodologies(),
            'allowEmpty' => true,
            'required' => false,
            'size' => 9, 'label-size' => 3,
            'class' => 'hidden'
        ));

        $fields_details_3['pm_reports_systems'] = $this->createElement( 'text', 'pm_reports_systems', array(
            'label' => 'Systemy raportowe',
            'filters' => array('StringTrim'),
            'value' => $this->_model->getPmReportsSystems(),
            'allowEmpty' => true,
            'required' => false,
            'size' => 9, 'label-size' => 3,
            'class' => 'hidden'
        ));

        $fields_details_3['knowledge_of_scrum'] = $this->createElement( 'checkbox', 'knowledge_of_scrum', array(
            'label' => 'Zna Scrum',
            'required' => false,
            'allowEmpty' => true,
            'size' => 9, 'label-size' => 3,
            'checked' =>  $this->_model->getKnowledgeOfScrum(),
            'class' => 'hidden'
        ));




        $this->addDisplayGroup($fields, 'main', array(
            'legend' => 'Główne informacje',
        ));

        $this->addDisplayGroup($fields_additional, 'contact', array(
            'legend' => 'Dane szczegółowe',
        ));

        $this->addDisplayGroup($fields_details_1, ' details_1', array(
            'class' => ' form-inputs-details'
        ));

        $this->addDisplayGroup($fields_details_2, ' details_2', array(
            'class' => ' form-inputs-details'
        ));


        $this->addDisplayGroup($fields_details_3, ' details_3', array(
            'class' => ' form-inputs-details'
        ));

        $save = $this->createElement('button', 'submit', array(
            'label' => 'Zapisz',
            'icon' => 'save',
            'type' => 'submit',
            'btnClass' => 'success'
        ));

        $this->setFormActions(array($save));
        $this->addElements(array($save));
    }


}