<?php

class Base_Form_Vertical extends Base_Form
{

    public function __construct($options = null)
    {
        $this->_initializePrefixes();

        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('InputGroup', array('class' => 'input-group')),
            array('ElementErrors'),
            array('Description', array('tag' => 'p', 'class' => 'help-block')),
            array('Label', array('class' => 'control-label')),
            array('WrapElement')
        ));
        
        parent::__construct($options);
    }
}
