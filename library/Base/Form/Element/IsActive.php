<?php


class Base_Form_Element_IsActive extends Base_Form_Element_Select
{

    private $_defaultName = 'is_active';


    public function __construct($name = null, $options = array())
    {
        empty($name) && $name = $this->_defaultName;

        if ( !array_key_exists('label', $options) ) {
            $options['label'] = 'field_filter_is_active';
        }

        if ( !array_key_exists('multioptions', $options) ) {
            $this->_loadMultiOptions($options);
        }

        $options['prefixPath'] = array(
            'decorator' => array('Base_Form_Decorator' => 'Base/Form/Decorator')
        );



        return parent::__construct($name, $options);
    }

    private function _loadMultiOptions(&$options)
    {
        $options['multioptions'] = array();

        if(!isset($options['required']) || !$options['required']){
            $options['multioptions'][''] = '';
        }

        $options['multioptions'][0] = 'label_no';
        $options['multioptions'][1] = 'label_yes';


        $options['validators'][] = array('InArray', true, array(array_keys($options['multioptions'])));
    }
}