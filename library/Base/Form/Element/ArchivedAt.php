<?php


class Base_Form_Element_ArchivedAt extends Base_Form_Element_Select
{

    private $_defaultName = 'archived_at';


    public function __construct($name = null, $options = array())
    {
        empty($name) && $name = $this->_defaultName;

        if ( !array_key_exists('label', $options) ) {
            $options['label'] = 'field_filter_archived-at';
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
        $options['multioptions'] = array(
            '' => '',
            '0' => 'label-filter_archive_all',
            '1' => 'label-filter_archive_yes',
        );

        $options['validators'][] = array('InArray', true, array(array_keys($options['multioptions'])));
    }
}