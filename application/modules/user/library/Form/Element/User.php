<?php

class User_Form_Element_User extends Base_Form_Element_Select
{
    private $_defaultName       = 'id_user';
    private $_userList          = array();

    public function __construct($name = null, $options = array())
    {
        empty($name) && $name = $this->_defaultName;

        if ( !array_key_exists('label', $options) ) {
            $options['label'] = 'field_user';
        }

        if ( !array_key_exists('multioptions', $options) ) {
            $this->_loadMultiOptions($options);
        }

//        $options['select2'] = array(
//            'allowClear' => true
//        );

        $options['prefixPath'] = array(
            'decorator' => array('Base_Form_Decorator' => 'Base/Form/Decorator')
        );

        return parent::__construct($name, $options);
    }

    public function getUserList()
    {
        return $this->_userList;
    }

    private function _loadMultiOptions(&$options)
    {
        $multiOptions = array();
        if(!isset($options['required']) || !$options['required']){
            $multiOptions[''] = '';
        }

        $this->_userList = User::getList(array('coll_key' => 'id_user'));



        foreach ($this->_userList as $id_user => $v) {
            $_name = $v['name']. ' ' . $v['surname'];
            $multiOptions[$id_user] = $_name;
        }

        if(isset($options['aclListUsers']) && $options['aclListUsers']) {
            $multiOptions = array('' => '') + $multiOptions;
        }

        $options['multioptions'] = $multiOptions;


        if(isset($options['searchable']) && $options['searchable']){
            $this->_registerInArrayValidator = false;
        }
        //  array('' => '')
//        else{
//            $options['validators'][] = array('InArray', true, array(array_keys($multiOptions)));
//        }
    }
}