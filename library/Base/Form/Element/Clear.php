<?php

class Base_Form_Element_Clear extends Zend_Form_Element_Submit
{

    public $helper = 'formClear';

    /**
     * Class constructor
     *
     * @param $spec
     * @param array $options
     */
    public function __construct($spec, $options = null)
    {
        if(!isset($options['label'])){ $options['label'] = 'cms_button_clear'; }

        parent::__construct($spec, $options);
    }
}

