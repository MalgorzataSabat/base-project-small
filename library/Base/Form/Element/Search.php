<?php


class Base_Form_Element_Search extends Zend_Form_Element
{

    public $helper = 'formSearchNumber';

    protected $_isArray = true;

    /**
     * Get fully qualified name
     *
     * Places name as subitem of array and/or appends brackets.
     *
     * @return string
     */
    public function getFullyQualifiedName()
    {
        $name = $this->getName();

        if (null !== ($belongsTo = $this->getBelongsTo())) {
            $name = $belongsTo . '[' . $name . ']';
        }

        return $name;
    }

    /**
     * Set element value
     *
     * @param  mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        $value  = (array)$value;

//        var_dump($value);

        !isset($value['type']) && $value['type'] = '';
        !isset($value['min']) && $value['min'] = '';
        !isset($value['max']) && $value['max'] = '';

//        var_dump($value);
//        exit();

        $this->_value = $value;
        return $this;
    }

    /**
     * Validate element value
     *
     * If a translation adapter is registered, any error messages will be
     * translated according to the current locale, using the given error code;
     * if no matching translation is found, the original message will be
     * utilized.
     *
     * Note: The *filtered* value is validated.
     *
     * @param  mixed $value
     * @param  mixed $context
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        $this->setValue($value);
        $value = $this->getValue();

        if(isset($value['type'])){
            unset($value['type']);
        }

        if ((('' === $value) || (null === $value))
            && !$this->isRequired()
            && $this->getAllowEmpty()
        ) {
            return true;
        }

        if ($this->isRequired()
            && $this->autoInsertNotEmptyValidator()
            && !$this->getValidator('NotEmpty'))
        {
            $validators = $this->getValidators();
            $notEmpty   = array('validator' => 'NotEmpty', 'breakChainOnFailure' => true);
            array_unshift($validators, $notEmpty);
            $this->setValidators($validators);
        }

        // Find the correct translator. Zend_Validate_Abstract::getDefaultTranslator()
        // will get either the static translator attached to Zend_Validate_Abstract
        // or the 'Zend_Translate' from Zend_Registry.
        if (Zend_Validate_Abstract::hasDefaultTranslator() &&
            !Zend_Form::hasDefaultTranslator())
        {
            $translator = Zend_Validate_Abstract::getDefaultTranslator();
            if ($this->hasTranslator()) {
                // only pick up this element's translator if it was attached directly.
                $translator = $this->getTranslator();
            }
        } else {
            $translator = $this->getTranslator();
        }

        $this->_messages = array();
        $this->_errors   = array();
        $result          = true;
        $isArray         = $this->isArray();
        foreach ($this->getValidators() as $key => $validator) {
            if (method_exists($validator, 'setTranslator')) {
                if (method_exists($validator, 'hasTranslator')) {
                    if (!$validator->hasTranslator()) {
                        $validator->setTranslator($translator);
                    }
                } else {
                    $validator->setTranslator($translator);
                }
            }

            if (method_exists($validator, 'setDisableTranslator')) {
                $validator->setDisableTranslator($this->translatorIsDisabled());
            }

            if ($isArray && is_array($value)) {
                $messages = array();
                $errors   = array();
                if (empty($value)) {
                    if ($this->isRequired()
                        || (!$this->isRequired() && !$this->getAllowEmpty())
                    ) {
                        $value = '';
                    }
                }
                foreach ((array)$value as $val) {
                    if ((('' === $val) || (null === $val))
                        && !$this->isRequired()
                        && $this->getAllowEmpty()
                    ) {
                        continue;
                    }

                    if (!$validator->isValid($val, $context)) {
                        $result = false;
                        if ($this->_hasErrorMessages()) {
                            $messages = $this->_getErrorMessages();
                            $errors   = $messages;
                        } else {
                            $messages = array_merge($messages, $validator->getMessages());
                            $errors   = array_merge($errors,   $validator->getErrors());
                        }
                    }
                }
                if ($result) {
                    continue;
                }
            } elseif ($validator->isValid($value, $context)) {
                continue;
            } else {
                $result = false;
                if ($this->_hasErrorMessages()) {
                    $messages = $this->_getErrorMessages();
                    $errors   = $messages;
                } else {
                    $messages = $validator->getMessages();
                    $errors   = array_keys($messages);
                }
            }

            $result          = false;
            $this->_messages = array_merge($this->_messages, $messages);
            $this->_errors   = array_merge($this->_errors,   $errors);

            if ($validator->zfBreakChainOnFailure) {
                break;
            }
        }

        // If element manually flagged as invalid, return false
        if ($this->_isErrorForced) {
            return false;
        }

        return $result;
    }
}