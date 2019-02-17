<?php

class Layout_Form_Element_LayoutTemplate extends Base_Form_Element_Select
{
    private $_defaultName  = 'id_layout_template';
    private $_list = array();
    private $_defaultModel = null;

    public function __construct($name = null, $options = array())
    {
        empty($name) && $name = $this->_defaultName;

        if ( !array_key_exists('label', $options) ) {
            $options['label'] = 'field_layout_template';
        }

        if ( !array_key_exists('multioptions', $options) ) {
            $this->_loadMultiOptions($options);
        }

        if(!isset($options['value']) || (isset($options['value']) && !$options['value'])){
            if(isset($options['use_default']) && $options['use_default'] && $this->_defaultModel){
                unset($options['use_default']);
                $options['value'] = $this->_defaultModel['id_layout_template'];
            }
        }

        $options['prefixPath'] = array(
            'decorator' => array('Base_Form_Decorator' => 'Base/Form/Decorator')
        );

        return parent::__construct($name, $options);
    }


    private function _loadMultiOptions(&$options)
    {
        $multiOptions = array();
        if(!isset($options['required']) || !$options['required']){
            $multiOptions[''] = '';
        }

        $this->_list = LayoutTemplate::getList(array('is_system' => 0, 'coll_key' => 'id_layout_template'));

        foreach ($this->_list as $id => $v) {
            $multiOptions[$id] = $v['name'];
            if($v['is_default']){
                $this->_defaultModel = $v;
            }
        }

        $options['multioptions'] = $multiOptions;
    }
}