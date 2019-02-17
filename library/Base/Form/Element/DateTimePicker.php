<?php


class Base_Form_Element_DateTimePicker extends Zend_Form_Element {

    public $helper = 'formDateTimePicker';

    public function loadDefaultDecorators() {}

    /**
     * Class constructor
     *
     * @param $spec
     * @param array $options
     */
    public function __construct($spec, $options = null)
    {
//        $options['class'] = trim(@$options['class'].' form-control');

        parent::__construct($spec, $options);
    }
}