<?php

class Base_Form_Inline extends Base_Form
{
    public function __construct($options = null)
    {
        $this->_initializePrefixes();
        
        $this->setDisposition(self::DISPOSITION_INLINE);

        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('InputGroup', array('class' => 'input-group')),
            array('Description', array('tag' => 'p', 'class' => 'help-block')),
            array('Label', array('class' => 'sr-only')),
            array('WrapElement'),
        ));
        
        parent::__construct($options);
    }
}
