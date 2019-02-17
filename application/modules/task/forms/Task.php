<?php

/**
 * Created by PhpStorm.
 * User: skotar
 * Date: 15.09.2016
 * Time: 19:25
 */
class Task_Form_Task extends Base_Form_Horizontal
{

    /**
     * @var $_model Task
     */
    protected $_model = null;

    private $_timeDurationList = array(
        '1800' => array('name' => '30 min', 'display_end_date' => 0, 'is_all_day' => 0),
        '3600' => array('name' => '1h', 'display_end_date' => 0, 'is_all_day' => 0),
        '7200' => array('name' => '2h', 'display_end_date' => 0, 'is_all_day' => 0),
        '14400' => array('name' => '4h', 'display_end_date' => 0, 'is_all_day' => 0),
        '28800' => array('name' => '8h', 'display_end_date' => 0, 'is_all_day' => 0),
        '86400' => array('name' => 'cały ', 'display_end_date' => 0, 'is_all_day' => 1),
        '172800' => array('name' => '2 dni', 'display_end_date' => 0, 'is_all_day' => 1),
        '259200' => array('name' => '3 dni', 'display_end_date' => 0, 'is_all_day' => 1),
        '' => array('name' => 'inny', 'display_end_date' => 1, 'is_all_day' => 0),
    );

    protected $_fieldsTime = array();

    protected $_fieldsExtra = array();

    protected $_fieldsRel = array();


    /**
     * @param Task $model
     */
    protected function setModel($model)
    {
        if($model->isNew()){
            if($model['start'] && $model['end']){
                $timeDiff = strtotime($model['end']) - strtotime($model['start']);
                if(isset($this->_timeDurationList[$timeDiff])){
                    $model->time_duration = $timeDiff;
                }else{
                    $model->time_duration = '';
                }
            }else{
                $model->time_duration = 3600;
            }
        }elseif(!isset($this->_timeDurationList[$model->time_duration])){
            $model->time_duration = '';
        }

        parent::setModel($model);
    }


    public function init()
    {
        $fields = array();
//        $fieldsExtra = array();
        $fieldsResult = array();

        // ----------------------- subject field -------------------------- //
        $fields['subject'] = $this->createElement('text', 'subject', array(
            'label' => Base::getFiledNameLabel('task.subject'),
            'allowEmpty' => false,
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', true)
            ),
            'label-size' => 2,
            'size' => 10,
            'value' => $this->_model->getSubject(),
        ));
        // ----------------------- subject field -------------------------- //




        // **************** ID_TYPE + ID_STATUS ROW **************** //
        $fields['id_type'] = new Task_Form_Element_Type('id_type', array(
            'label' => Base::getFiledNameLabel('task.id_type'),
            'object' => 'TaskType',
            'value' => $this->_model['id_type'],
            'required' => true,
            'allowEmpty' => false,
            'label-size' => 4,
            'size' => 8,
            'decorators' => $this->_elementDecorators,
            'filters' => array('Null'),
            'use_default' => true,
        ));

        $fields['id_status'] = new Dictionary_Form_Element_Select('id_status', array(
            'label' => Base::getFiledNameLabel('task.id_status'),
            'object' => 'TaskStatus',
            'value' => $this->_model['id_status'],
            'required' => true,
            'allowEmpty' => false,
            'label-size' => 4,
            'size' => 8,
            'decorators' => $this->_elementDecorators,
            'filters' => array('Null'),
            'use_default' => true,
        ));

        $this->addHtmlTag(array($fields['id_type']), array('class' => 'col-md-6'));
        $this->addHtmlTag(array($fields['id_status']), array('class' => 'col-md-6'));
        $this->addHtmlTag(array($fields['id_type'], $fields['id_status']), array('class' => 'row'));
        // **************** ID_TYPE + ID_STATUS ROW **************** //



        // ******************* ID_USER + ID_TASK_PRIORITY ROW ******************* //
        $fields['id_user'] = new User_Form_Element_User('id_user', array(
            'label' => Base::getFiledNameLabel('task.id_user'),
            'value' => $this->_model->getIdUser(),
            'decorators' => $this->_elementDecorators,
            'allowEmpty' => false,
            'required' => true,
            'label-size' => 4,
            'size' => 8,
        ));

        $fields['id_task_priority'] = new Dictionary_Form_Element_Select('id_task_priority', array(
            'label' => Base::getFiledNameLabel('task.id_task_priority'),
            'object' => 'TaskPriority',
            'value' => $this->_model['id_task_priority'],
            'required' => true,
            'allowEmpty' => false,
            'label-size' => 4,
            'size' => 8,
            'decorators' => $this->_elementDecorators,
            'filters' => array('Null'),
            'use_default' => true,
        ));

        $this->addHtmlTag(array($fields['id_user']), array('class' => 'col-md-6'));
        $this->addHtmlTag(array($fields['id_task_priority']), array('class' => 'col-md-6'));
        $this->addHtmlTag(array($fields['id_user'], $fields['id_task_priority']), array('class' => 'row'));
        // ******************* ID_USER + ID_TASK_USER ROW ******************* //


        $fields['id_client'] = new Client_Form_Element_Client('id_client', array(
            'label' => Base::getFiledNameLabel('task.id_client'),
            'value' => $this->_model->getIdClient(),
            'decorators' => $this->_elementDecorators,
            'filters' => array('Null'),
            'label-size' => 4,
            'size' => 8,
//            'add-record' => true,
            'model' => $this->_model,
        ));

        $taskUserValue = array();
        if(!$this->_model->isNew()){
            $taskUserList = TaskUser::getList(array('id_task' => $this->_model['id_task']));

            foreach($taskUserList as $v){
                $taskUserValue[] = $v['id_user'];
            }
        }

        $fields['id_task_user'] = new User_Form_Element_User('id_task_user', array(
            'label' => Base::getFiledNameLabel('task.id_task_user'),
            'value' => $taskUserValue,
            'decorators' => $this->_elementDecorators,
            'allowEmpty' => true,
            'required' => false,
            'label-size' => 4,
            'size' => 8,
            'multiple' => 'multiple',
            'select2' => true,
            'registerInArrayValidator' => false,
        ));

        $this->addHtmlTag(array($fields['id_client']), array('class' => 'col-md-6'));
        $this->addHtmlTag(array($fields['id_task_user']), array('class' => 'col-md-6'));
        $this->addHtmlTag(array($fields['id_client'], $fields['id_task_user']), array('class' => 'row'));



        $this->_createFieldsRel();
        $fields = array_merge($fields, $this->_fieldsRel);

        $this->_createFieldsTime();
        $fields = array_merge($fields, $this->_fieldsTime);


        $this->_createFieldsExtra();

        // ------------- BEGIN: TASK RESULT FILEDS ------------------//
//        $fieldsResult['id_task_result'] = new Task_Form_Element_Result('id_task_result', array(
//            'value' => $this->_model['id_task_result'],
//            'decorators' => $this->_elementDecorators,
//            'id_type' => $fields['id_type']->getValue(),
//            'allowEmpty' => true,
//            'required' => false,
//            'filters' => array('Null'),
//            'label-size' => 4,
//            'size' => 8,
//        ));


        $fieldsResult['result_desc'] = $this->createElement('textarea', 'result_desc', array(
//            'label' => $this->_tlabel.'result_desc',
            'required' => false,
            'allowEmpty' => true,
            'value' => $this->_model['result_desc'],
            'rows' => 5,
            'style' => 'height: 134px',
            'filters' => array('StringTrim'),
//            'label-size' => 4,
            'size' => 12,
        ));
        // ------------- END: TASK RESULT FILEDS ------------------//


        $this->addDisplayGroup($fields, 'main', array(
            'legend' => $this->_tlabel.'group_std',
            'escape' => false,
            'style' => 'margin-bottom: 20px;'
        ));

        $this->addDisplayGroup($this->_fieldsExtra, 'desc', array(
            'legend' => $this->_tlabel.'group_extra',
            'class' => 'col-md-7',
            'escape' => false
        ));

        $this->addDisplayGroup($fieldsResult, 'result', array(
            'legend' => $this->_tlabel.'group_result',
            'class' => 'col-md-5',
            'escape' => false
        ));


        $descGroup = $this->getDisplayGroup('desc');
        $resultGroup = $this->getDisplayGroup('result');

        $this->addHtmlTag(array($descGroup, $resultGroup), array('class' => 'row'));


        // ------------- SUBMIT FILEDS ------------------//
        $submitAction = $this->createElement('hidden', 'submit_action');
        $submitAction->removeDecorator('WrapElement')->removeDecorator('FieldSize');

        $taskStatus = Dictionary::getById($this->_model['id_status']);

        if($taskStatus && $taskStatus['is_closed']){
            $saveNext = $this->createElement('button', 'submit_next', array(
                'label' => 'cms_button_save_next',
                'icon' => 'chevron-right',
                'type' => 'submit',
                'btnClass' => 'default',
                'data-action' => 'next',
            ));
        }else{
            $saveNext = $this->createElement('button', 'submit_work', array(
                'label' => 'cms_button_save_work',
                'icon' => 'chevron-right',
                'type' => 'submit',
                'btnClass' => 'default',
                'data-action' => 'work',
            ));
        }

        $save = $this->createElement('button', 'submit', array(
            'label' => 'cms_button_save',
            'icon' => 'save',
            'type' => 'submit',
        ));

        $this->setFormActions(array($save, $saveNext));
        $this->addElements(array($submitAction, $save, $saveNext));
    }

    private function _createFieldsRel()
    {
        $filedsRow = array();

        if(Setting::getSetting('task.agreement_on')){
            $filedsRow['id_agreement'] = new Agreement_Form_Element_Select('id_agreement', array(
                'label' => Base::getFiledNameLabel('task.id_agreement'),
                'value' => $this->_model['id_agreement'],
                'required' => false,
                'allowEmpty' => true,
                'label-size' => 4,
                'size' => 8,
                'id_client' => $this->_model['id_client'],
                'decorators' => $this->_elementDecorators,
                'filters' => array('Null'),
            ));

            $filedsRow['id_agreement_item'] = new Agreement_Form_Element_Item('id_agreement_item', array(
                'label' => Base::getFiledNameLabel('task.id_agreement_item'),
                'value' => $this->_model['id_agreement_item'],
                'required' => false,
                'allowEmpty' => true,
                'label-size' => 4,
                'size' => 8,
                'id_agreement' => $this->_model['id_agreement'],
                'decorators' => $this->_elementDecorators,
                'filters' => array('Null'),
            ));

            $this->addHtmlTag($filedsRow, array('class' => 'col-md-6'));

        }

        $filedsRow['id_project'] = new Project_Form_Element_Project('id_project', array(
            'label' => Base::getFiledNameLabel('task.id_project'),
            'value' => $this->_model->getIdProject(),
            'decorators' => $this->_elementDecorators,
            'filters' => array('Null'),
            'id_client' => $this->_model['id_client'],
            'label-size' => 4,
            'size' => 8,
            'add-record' => true,
        ));

        $this->addHtmlTag(array($filedsRow['id_project']), array('class' => 'col-md-6'));


        $this->addHtmlTag($filedsRow, array('class' => 'row'));
        $this->_fieldsRel += $filedsRow;

    }


    private function _createFieldsTime()
    {
        $diff = 0;
        if($this->_model->getStart() == $this->_model->getEnd() && !$this->_model->getIsAllDay()){
            $diff = 60 * 60; // + 1 hour
            $this->_model['time_duration'] = $diff;
        }

        $startDate = date('Y-m-d', strtotime($this->_model->getStart()));
        $startTime = date('H:i', strtotime($this->_model->getStart()));
//        if($startTime == '00:00'){ $startTime = date('H:i'); }

        $this->_fieldsTime['date_start'] = $this->createElement('DateTimePicker', 'date_start', array(
            'label' => Base::getFiledNameLabel('task.date_start'),
            'allowEmpty' => true,
            'datetime-format' => 'date',
            'validators' => array(
                array('Date', true, array('format' => 'yyyy-MM-dd'))
            ),
            'value' => $startDate,
            'label-size' => 2,
            'size' => 3,
            'autocomplete' => 'off'
        ));

        $this->_fieldsTime['time_start'] = $this->createElement('DateTimePicker', 'time_start', array(
            'allowEmpty' => true,
            'datetime-format' => 'time',
            'addon-icon' => 'clock-o',
            'validators' => array('Time'),
            'value' => $startTime,
            'data-value' => $startTime,
            'size' => 3,
            'class' => 'taskTimeCol',
            'autocomplete' => 'off',
        ))->removeDecorator('Label');

        $timeDurationOptions = array();
        foreach($this->_timeDurationList as $k => $v){
            $timeDurationOptions[$k] = $v['name'];
        }

        $this->_fieldsTime['time_duration'] = $this->createElement('select', 'time_duration', array(
            'value' => $this->_model['time_duration'] ? $this->_model['time_duration'] : '',
            'time_duration' => $this->_model['time_duration'],
            'input-group' => array('pre' => $this->getView()->translate($this->_tlabel.'time_duration')),
            'label' => $this->_tlabel.'time_duration',
            'multiOptions' => $timeDurationOptions,
            'data-time-duration' => json_encode($this->_timeDurationList),
            'size' => 4,
        ));

        $this->_fieldsTime['date_start']->removeDecorator('WrapElement');
        $this->_fieldsTime['time_start']->removeDecorator('Label');
        $this->_fieldsTime['time_start']->removeDecorator('WrapElement');
        $this->_fieldsTime['time_duration']->removeDecorator('Label');
        $this->_fieldsTime['time_duration']->removeDecorator('WrapElement');

        $this->_fieldsTime['time_start']->getDecorator('FieldSize')->setOption('class', 'taskTimeCol');

        $this->addHtmlTag(array($this->_fieldsTime['date_start'], $this->_fieldsTime['time_duration']), array('class' => 'form-group '));
        $this->addHtmlTag(array($this->_fieldsTime['date_start'], $this->_fieldsTime['time_duration']), array('class' => 'col-md-12'));
        $this->addHtmlTag(array($this->_fieldsTime['date_start'], $this->_fieldsTime['time_duration']), array('class' => 'row taskStartRow'));
        // ******************* DATE/TIME START ROW ******************* //



        if(!$this->_model->isNew() && $this->_model->is_all_day){
            $endDate = date('Y-m-d', strtotime($this->_model->getEnd().' - 1 days'));
        }else{
            $endDate = date('Y-m-d', strtotime($this->_model->getEnd()));
        }


        $endTime   = date('H:i', strtotime($this->_model->getEnd()) + $diff);
//        if($endTime == '00:00'){ $endTime = date('H:i', strtotime(' + 60 minutes')); }

        $this->_fieldsTime['date_end'] = $this->createElement('DateTimePicker', 'date_end', array(
            'label' => Base::getFiledNameLabel('task.date_end'),
            'allowEmpty' => true,
            'datetime-format' => 'date',
            'validators' => array(
                array('Date', true, array('format' => 'yyyy-MM-dd'))
            ),
            'label-size' => 2,
            'size' => 3,
            'value' => $endDate,
            'autocomplete' => 'off'
        ));

        $this->_fieldsTime['time_end'] = $this->createElement('DateTimePicker', 'time_end', array(
            'allowEmpty' => true,
            'datetime-format' => 'time',
            'validators' => array('Time'),
            'size' => 3,
            'addon-icon' => 'clock-o',
            'value' => $endTime,
            'data-value' => $endTime,
            'autocomplete' => 'off'
        ));

        $this->_fieldsTime['time_end']->getDecorator('FieldSize')->setOption('class', 'taskTimeCol');

        $this->_fieldsTime['is_all_day'] = $this->createElement('checkbox', 'is_all_day', array(
//            'label' => $this->_tlabel.'is_all_day',
            'text' => $this->getView()->translate($this->_tlabel.'is_all_day'),
            'value' => $this->_model['is_all_day'],
            'size' => 3,
        ));

        $this->_fieldsTime['date_end']->removeDecorator('WrapElement');
        $this->_fieldsTime['time_end']->removeDecorator('Label');
        $this->_fieldsTime['time_end']->removeDecorator('WrapElement');
        $this->_fieldsTime['is_all_day']->removeDecorator('Label');
        $this->_fieldsTime['is_all_day']->removeDecorator('WrapElement');

        $this->addHtmlTag(array($this->_fieldsTime['date_end'], $this->_fieldsTime['is_all_day']), array('class' => 'form-group '));
        $this->addHtmlTag(array($this->_fieldsTime['date_end'], $this->_fieldsTime['is_all_day']), array('class' => 'col-md-12'));
        $this->addHtmlTag(array($this->_fieldsTime['date_end'], $this->_fieldsTime['is_all_day']), array('class' => 'row taskEndRow'), 'taskEndRow');

        $this->_fieldsTime['remind'] = $this->createElement('select', 'remind', array(
            'label' => Base::getFiledNameLabel('task.remind'),
            'required' => false,
            'allowEmpty' => true,
            'multioptions' => array(
                '' => 'Brak',
                '0' => '0 min',
                '5' => '5 min',
                '10' => '10 min',
                '15' => '15 min',
                '30' => '30 min',
                '60' => '1 godzina',
                '120' => '2 godziny',
                '180' => '3 godziny',
                '240' => '4 godziny',
                '480' => '8 godzin',
                '720' => '12 godzin',
                '1440' => '1 dzień',
                '2880' => '2 dni',
                '4320' => '3 dni',
                '10080' => '1 tydzień',
                '20160' => '2 tygodnie',
            ),
            'input-group' => array('post' => 'przed'),
            'filters' => array('StringTrim', 'Null'),
            'value' => $this->_model->getRemind(),
            'size' => 8,
            'label-size' => 4,
        ));

        $this->addHtmlTag(array($this->_fieldsTime['remind'], $this->_fieldsTime['remind']), array('class' => 'col-md-6'));
        $this->addHtmlTag(array($this->_fieldsTime['remind'], $this->_fieldsTime['remind']), array('class' => 'row'));


        $valTaskColOptions = array();
        !$this->_model->isNew() && $valTaskColOptions['id_task_not'] = $this->_model['id_task'];
        $this->_fieldsTime['ignore_busy'] = new Task_Form_Element_IgnoreBusy('ignore_busy', array(
            'label' => 'Termin zajęty',
            'value' => '0',
            'label-size' => 2,
            'size' => 10,
            'text' => 'Ignoruj kolizję zadań',
            'validators' => array(new Task_Validate_Colision($valTaskColOptions)),
            'decorators' => array(
                array('ViewHelper'),
                array('ElementErrors'),
                array(new Task_Form_Decorator_Colision()),
                array('InputGroup', array('class' => 'input-group')),
                array('Description', array('tag' => 'span', 'class' => 'help-block')),
                array('FieldSize', array('size' => 9)),
                array('Label', array('class' => 'control-label', 'size' => 3)),
                array('WrapElement'),
                array(new Task_Form_Decorator_ColisionWrap()),
            )
        ));

    }



    private function _createFieldsExtra()
    {

        $this->_fieldsExtra['deadline'] = $this->createElement('DateTimePicker', 'deadline', array(
            'label' => Base::getFiledNameLabel('task.deadline'),
            'value' => $this->_model['deadline'],
            'allowEmpty' => true,
            'datetime-format' => 'date',
            'filters' => array('Null'),
            'validators' => array(
                array('Date', true, array('format' => 'yyyy-MM-dd'))
            ),
            'size' => 9,
            'label-size' => 3,
        ));


        $this->_fieldsExtra['location'] = $this->createElement('text', 'location', array(
            'label' => Base::getFiledNameLabel('task.location'),
            'required' => false,
            'allowEmpty' => true,
            'filters' => array('StringTrim'),
            'value' => $this->_model->getLocation(),
        ));

        $this->_fieldsExtra['tags'] = new Tag_Form_Element_Tag('tags', array(
            'value' => !empty($this->_model['tags']) ? json_decode($this->_model['tags']) : array(),
            'size' => 9,
            'label-size' => 3,
            'allowEmpty' => true,
            'required' => false,
            'select2' => array(),
            'data-tags' => true,
            'data-token-separators' => "[',']",
            'multiple' => 'multiple',
            'decorators' => $this->_elementDecorators,
            'filters' => array('Null'),
            'model' => 'Task',
        ));

        $this->_fieldsExtra['description'] = $this->createElement('textarea', 'description', array(
            'label' => Base::getFiledNameLabel('task.description'),
            'required' => false,
            'allowEmpty' => true,
            'value' => $this->_model->getDescription(),
            'rows' => 3,
            'filters' => array('StringTrim'),
            'size' => 9,
            'label-size' => 3,
        ));

        if($this->_model->isNew() || $this->_model['is_series']){
            $this->_fieldsExtra['repeat_type'] = $this->createElement('select', 'repeat_type', array(
                'label' =>  Base::getFiledNameLabel('task.repeat_type'),
                'required' => false,
                'allowEmpty' => true,
                'multioptions' => array(
                    '' => 'Brak',
                    'day' => 'Codziennie',
                    'workday' => 'W dni roboczne',
                    'week' => 'Co tydzień',
                    'month' => 'Co miesiąc',
                ),
                'value' => $this->_model['repeat_type'],
                'filters' => array('StringTrim'),
            ));

            $defaultDate = $this->_fieldsTime['date_start']->getValue();
            !$defaultDate && $defaultDate = date('Y-m-d');
            $this->_fieldsExtra['repeat_start'] = $this->createElement('DateTimePicker', 'repeat_start', array(
                'allowEmpty' => true,
                'datetime-format' => 'date',
                'validators' => array(
                    array('Date', true, array('format' => 'yyyy-MM-dd'))
                ),
                'value' => $this->_model['repeat_start'] ? $this->_model['repeat_start'] : $defaultDate,
                'autocomplete' => 'off',
                'input-group' => array('pre' => 'od'),
                'size' => 6,
            ));

            $this->_fieldsExtra['repeat_end'] = $this->createElement('DateTimePicker', 'repeat_end', array(
                'allowEmpty' => true,
                'datetime-format' => 'date',
                'validators' => array(
                    array('Date', true, array('format' => 'yyyy-MM-dd'))
                ),
                'value' => $this->_model['repeat_end'] ? $this->_model['repeat_end'] : date('Y-m-d', strtotime('+ 1 month')),
                'autocomplete' => 'off',
                'input-group' => array('pre' => 'do'),
                'size' => 6,
            ));

            $this->_fieldsExtra['repeat_start']->removeDecorator('Label')->removeDecorator('WrapElement');
            $this->_fieldsExtra['repeat_end']->removeDecorator('Label')->removeDecorator('WrapElement');

            $this->addHtmlTag(array($this->_fieldsExtra['repeat_start'], $this->_fieldsExtra['repeat_end']), array('class' => 'form-group'));
            $this->addHtmlTag(array($this->_fieldsExtra['repeat_start'], $this->_fieldsExtra['repeat_end']), array('class' => 'col-md-offset-3 col-md-9'));
            $this->addHtmlTag(array($this->_fieldsExtra['repeat_start'], $this->_fieldsExtra['repeat_end']), array('class' => 'row repeatStartEndRow'), 'repeatStartEndRow');
        }
    }

    public function populate(array $value)
    {
        if(!empty($value['date_start']) && !empty($value['date_end']))
        {if(strtotime($value['date_start'] . ' ' . $value['time_start']) > strtotime($value['date_end']  . ' ' . $value['time_end']))
            {
                $tmpDate = $value['date_end'];
                $value['date_end'] = $value['date_start'];
                $value['date_start'] = $tmpDate;

                $tmpTime = $value['time_end'];
                $value['time_end'] = $value['time_start'];
                $value['time_start'] = $tmpTime;
            }
        }

        return parent::populate($value);
    }

    public function isValid($data)
    {
        $_data = $data[$this->getElementsBelongTo()];

        $idProjectElement = $this->getElement('id_project');
        $idProjectElement->loadMultiOptions(array('id_client' => $_data['id_client']));

        $idAgreementElement = $this->getElement('id_agreement');
        if($idAgreementElement){
            $idAgreementElement->loadMultiOptions(array('id_client' => $_data['id_client']));
            $multiOptions = $idAgreementElement->getMultiOptions();
            if(array_filter(array_keys($multiOptions)) && Setting::getSetting('task.agreement_required')){
                $idAgreementElement->setRequired(true);
                $idAgreementElement->setAllowEmpty(false);
            }


            $idAgreementItemElement = $this->getElement('id_agreement_item');
            if($idAgreementItemElement){
                $idAgreementItemElement->loadMultiOptions(array('id_agreement' => $_data['id_agreement']));
                $multiOptions = $idAgreementItemElement->getMultiOptions();
                if(array_filter(array_keys($multiOptions)) && Setting::getSetting('task.agreement_required')){
                    $idAgreementItemElement->setRequired(true);
                    $idAgreementItemElement->setAllowEmpty(false);
                }

            }
        }

        $isAllDayValue = $_data['is_all_day'];
        $timeDuration = $_data['time_duration'];

        $startTime = $_data['date_start'];
        $startTime.= $isAllDayValue ? ' 00:00:00' : ' '.$_data['time_start'].':00';

        if($this->_timeDurationList[$timeDuration]['display_end_date']){
            if($isAllDayValue){
                $endTime = date('Y-m-d H:i:s', strtotime($_data['date_end'])+$timeDuration);
            }else{
                $endTime = $_data['date_end'].' '.$_data['time_end'].':00';
            }
        }else{
            $endTime = date('Y-m-d H:i:s', strtotime($startTime)+$timeDuration);
        }

        if($startTime && $endTime && ($endTime < $startTime)){
            $endTime ^= $startTime ^= $endTime ^= $startTime;
        }

        $data[$this->getElementsBelongTo()]['date_start'] = date('Y-m-d', strtotime($startTime));
        $data[$this->getElementsBelongTo()]['time_start'] = date('H:i', strtotime($startTime));
        $data[$this->getElementsBelongTo()]['date_end'] = date('Y-m-d', strtotime($endTime));
        $data[$this->getElementsBelongTo()]['time_end'] = date('H:i', strtotime($endTime));


        if(isset($_data['repeat_type']) && $_data['repeat_type']){
            !$_data['repeat_start'] && $_data['repeat_start'] = date('Y-m-d');
            !$_data['repeat_end'] && $_data['repeat_end'] = date('Y-m-d', strtotime($_data['repeat_start'].' + 1 month'));


//            if($_data['repeat_start'] <= date('Y-m-d')){
//                $_data['repeat_start'] = date('Y-m-d');
//            }

//            if($_data['repeat_end'] <= date('Y-m-d')){
//                $_data['repeat_end'] = date('Y-m-d', strtotime($_data['repeat_start'].' + 1 year'));
//            }

            if($_data['repeat_start'] && $_data['repeat_end'] && ($_data['repeat_end'] < $_data['repeat_start'])){
                $_data['repeat_end'] ^= $_data['repeat_start'] ^= $_data['repeat_end'] ^= $_data['repeat_start'];
            }

            $data[$this->getElementsBelongTo()]['repeat_start'] = $_data['repeat_start'];
            $data[$this->getElementsBelongTo()]['repeat_end'] = $_data['repeat_end'];
        }

//        var_dump($data);
//        exit();

        return parent::isValid($data);
    }

    /**
     * @param Zend_View_Interface|null $view
     * @return string
     */
    public function render(Zend_View_Interface $view = null)
    {
        $ignoreBusyElement = $this->getElement('ignore_busy');
        $colisionDecorator = $ignoreBusyElement->getDecorator('Task_Form_Decorator_Colision');
        if($colisionDecorator){
            $colisionDecorator->setOption('task-options', array(
                'context' => $this->getValues(true),
                'id_task_not' => $this->_model['id_task'],
            ));
        }

        $time_duration_value = $this->time_duration->getValue();
        if(!$this->_timeDurationList[$time_duration_value]['display_end_date']){
            $this->getElement('date_end')->getDecorator('taskEndRow')->setOption('style', 'display:none;');
        }

        $isAllDayValue = $this->is_all_day->getValue();
        if($isAllDayValue){
            $this->getElement('time_start')->getDecorator('FieldSize')->setOption('style', 'display:none;');
            $this->getElement('time_end')->getDecorator('FieldSize')->setOption('style', 'display:none;');
        }

        $repeatTypeElement = $this->getElement('repeat_type');
        if($repeatTypeElement){
            $repeat_type_value = $repeatTypeElement->getValue();
            if(!$repeat_type_value){
                $this->getElement('repeat_start')->getDecorator('repeatStartEndRow')->setOption('style', 'display:none;');
            }
        }

        return parent::render($view);
    }



    public function postIsValid($data)
    {
        parent::postIsValid($data);

        $startTime = $this->getValue('date_start').' '.$this->getValue('time_start').':00';
        $endTime = $this->getValue('date_end').' '.$this->getValue('time_end').':00';
//        $timeDuration = strtotime($endTime) - strtotime($startTime);

        $this->_model->setStart($startTime);
        $this->_model->setEnd($endTime);
//        $this->_model->setTimeDuration($timeDuration);

        $this->_setRepeatValue();

        $this->_model->save();

        TaskUser::removeRecord($this->_model->getId());
        $idTaskUsers = (array)$this->id_task_user->getValue();
        foreach ($idTaskUsers as $v) {
            $taskUser = new TaskUser();
            $taskUser->setIdTask($this->_model->getId());
            $taskUser->setIdUser($v);
            $taskUser->save();
        }

        return true;
    }



    private function _setRepeatValue()
    {
        if(!$this->_model['repeat_type']){
            if($this->_model['is_series']){
                $taskSeries = new Task_Series($this->_model);
                $taskSeries->delete();
            }

            $this->_model['is_series'] = false;
            $this->_model['repeat_day_of_week'] = '[]';
            $this->_model['repeat_start'] = null;
            $this->_model['repeat_end'] = null;

            return;
        }

        $this->_model['is_series'] = true;

        $dayOfWeek = array();
        if($this->_model['repeat_type'] == 'workday') {
            $dayOfWeek = array(1, 2, 3, 4, 5);
        }elseif($this->_model['repeat_type'] == 'month'){
            $this->_model['repeat_day_of_month'] = (int) date('d', strtotime($this->_model['start']));
        }else{
            $dayOfWeek = array((int) date('N', strtotime($this->_model['start'])));
        }

        $this->_model['repeat_day_of_week'] = json_encode($dayOfWeek, JSON_UNESCAPED_UNICODE);
    }
}