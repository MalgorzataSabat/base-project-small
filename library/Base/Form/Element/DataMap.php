<?php

class Base_Form_Element_DataMap extends Zend_Form_Element_Textarea
{

    public $helper = 'formDataMap';

    private $_placeholders = array();

    /**
     * @var Base_Validate_DataMap
     */
    private $_dataMapValidator;

    public function __construct($spec, $options = null)
    {
        parent::__construct($spec, $options);

        $this->setBelongsTo($options['belongs']);
        $this->createDataMap($this->getValue());

        $this->_dataMapValidator = new Base_Validate_DataMap();
        $this->_dataMapValidator->setPlaceholders($this->_placeholders);
        $this->addValidator($this->_dataMapValidator, false);
    }


    public function isValid($value, $context = null)
    {
        $this->createDataMap($value);
        $this->_dataMapValidator->setPlaceholders($this->_placeholders);

        return parent::isValid($value, $context);
    }


    private function createDataMap($value)
    {
        $value = (array)$value;

        foreach($value as $placeholder => &$definitions)
        {
            if(empty($definitions)) { continue; }
            foreach($definitions['widgets'] as $k => &$widget){
                // dodane $this->_belongsTo
                $widget['_BelongsTo'] = $this->_belongsTo.'[data_map]['.$placeholder.'][widgets]['.$k.']';
                if(class_exists($widget['_class'])){
                    $w = new $widget['_class']();
                    $widget['_form'] = $w->form($widget);
                }
            }
        }
    }

    /**
     * Adds a file decorator if no one found
     */
    public function loadDefaultDecorators()
    {
        $this->clearDecorators();

        $this->setDecorators(array(
//            array('FieldSize'),
            array('ViewHelper'),
//            array('Addon'),
//            array('ElementErrors'),
            array('Description', array('tag' => 'p', 'class' => 'help-block')),
            array('HtmlTag', array('tag' => 'div', 'class' => 'placeholders-controls', 'id' => 'placeholders-controls')),
        ));

        return $this;
    }

}

