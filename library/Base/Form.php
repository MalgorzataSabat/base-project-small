<?php

abstract class Base_Form extends Zend_Form
{
    /**
     * Class constants
     */
    const DISPOSITION_HORIZONTAL = 'horizontal';
    const DISPOSITION_INLINE     = 'inline';
    const DISPOSITION_SEARCH     = 'search';

    protected $_prefixesInitialized = false;

    protected $_belong_to = '';

    protected $_tlabel = '';

    /**
     * @var Base_Doctrine_Record
     */
    protected $_model = null;

    protected $_merge_model = true;

    protected $_disposition = null;

    /**
     * @param $model Doctrine_Record
     */
    protected function setModel($model)
    {
        $this->_model = $model;
    }

    /**
     * Override the base form constructor.
     *
     * @param null $options
     */
    public function __construct($options = null)
    {
        $this->_initializePrefixes();
        $this->setAttrib('novalidate', 'novalidate');

        if(empty($this->_belong_to)){
            $this->_belong_to = get_called_class();
        }
        $this->setElementsBelongTo($this->_belong_to);

        if(empty($this->_tlabel)){
            $this->_tlabel = strtolower(get_called_class()).'_';
        }

        $this->setDecorators(array(
            'FormElements',
            array('Form', array('role' => 'form'))
        ));

        parent::__construct($options);
    }


    protected function _initializePrefixes()
    {
        if (!$this->_prefixesInitialized){
            $this->addPrefixPath('Base_Form_Element', 'Base/Form/Element', 'element');
            $this->addElementPrefixPath('Base_Form_Decorator', 'Base/Form/Decorator', 'decorator');
            $this->addDisplayGroupPrefixPath('Base_Form_Decorator', 'Base/Form/Decorator' );
            $this->addElementPrefixPath('Base_Validate', 'Base/Validate/', 'validate');
            $this->addElementPrefixPath('Base_Filter', 'Base/Filter/', 'filter');
            $this->setDefaultDisplayGroupClass('Base_Form_DisplayGroup');

            $this->_prefixesInitialized = true;
        }
    }

    /**
     * @param string $disposition
     */
    public function setDisposition($disposition)
    {
        if (
        in_array(
            $disposition,
            array(
                self::DISPOSITION_HORIZONTAL,
                self::DISPOSITION_INLINE,
                self::DISPOSITION_SEARCH
            )
        )
        ) {
            $this->_addClassNames('form-' . $disposition);
            $this->_disposition = $disposition;
        }
    }

    public function getDisposition()
    {
        return $this->_disposition;
    }

    /**
     * Adds a class name
     *
     * @param string $classNames
     */
    protected function _addClassNames($classNames)
    {
        $classes = $this->_getClassNames();

        foreach ((array) $classNames as $className) {
            $classes[] = $className;
        }

        $this->setAttrib('class', trim(implode(' ', array_unique($classes))));
    }

    /**
     * Removes a class name
     *
     * @param string $classNames
     */
    protected function _removeClassNames($classNames)
    {
        $this->setAttrib('class', trim(implode(' ', array_diff($this->_getClassNames(), (array) $classNames))));
    }

    /**
     * Extract the class names from a Zend_Form_Element if given or from the
     * base form
     *
     * @param Zend_Form_Element $element
     * @return array
     */
    protected function _getClassNames(Zend_Form_Element $element = null)
    {
        if (null !== $element) {
            return explode(' ', $element->getAttrib('class'));
        }

        return explode(' ', $this->getAttrib('class'));
    }


    public function addHtmlTag($elements, $options = array(), $decorator_name = null)
    {
        $elements = array_values((array)$elements);

        $options_open = array_merge($options, array('openOnly' => true));
        $options_close = array_merge($options, array('closeOnly' => true));

        $decorator_name = empty($decorator_name) ? count($elements[0]->getDecorators()) : $decorator_name;
        $dec_open_name = 'open_'.$decorator_name;
        $dec_close_name = 'close_'.$decorator_name;

        $elements[0]->addDecorator(array($dec_open_name => 'HtmlTag'), $options_open);
        $elements[count($elements)-1]->addDecorator(array($dec_close_name => 'HtmlTag'), $options_close);

    }

    /**
     * @param $elements
     * @return $this
     */
    public function setFormActions($elements)
    {
        $elements = (array) $elements;
        foreach($elements as $el){
            $el->setDecorators(array('ViewHelper'));
        }

        $this->addHtmlTag($elements, array('class' => 'form-actions'));

        return $this;
    }


    public function isValid($data)
    {
        return parent::isValid($data) && $this->postIsValid($data);
    }

    protected function postIsValid($data)
    {
        if($this->_merge_model && null !== $this->_model && $this->_model instanceof Base_Doctrine_Record){
            $this->_model->setFields($this->getValues($this->_belong_to));
        }

        return true;
    }
}
