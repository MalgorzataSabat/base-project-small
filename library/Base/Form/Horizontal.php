<?php

class Base_Form_Horizontal extends Base_Form
{

    public $defaultElementDecorators = array(
        array('ViewHelper'),
        array('InputGroup', array('class' => 'input-group')),
        array('ElementErrors'),
        array('Description', array('tag' => 'span', 'class' => 'help-block')),
        array('FieldSize', array('size' => 9)),
        array('Label', array('class' => 'control-label', 'size' => 3)),
        array('WrapElement')
    );

    public function __construct($options = null)
    {
        $this->_initializePrefixes();

        $this->setDisposition(self::DISPOSITION_HORIZONTAL);

        $this->setElementDecorators($this->defaultElementDecorators);

        parent::__construct($options);
    }

}
