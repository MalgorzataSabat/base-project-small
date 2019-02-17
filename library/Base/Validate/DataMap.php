<?php


class Base_Validate_DataMap extends Zend_Validate_Abstract
{

    private $_placeholders = array();


    public function __construct($options = array())
    {
        if(isset($options['placeholders'])){
            $this->setPlaceholders((array) @$options['placeholders']);
        }

    }



    public function setPlaceholders($placeholders)
    {
        $this->_placeholders = $placeholders;

        return $this;
    }

    public function getPlaceholders()
    {
        return $this->_placeholders;
    }

    /**
     * Validate element value.
     *
     * @param  mixed $value
     * @param  mixed $context
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        $isVaild = true;

        foreach($this->_placeholders as $placeholder_name => &$placeholder){
            foreach($placeholder['widgets'] as $key => &$widget){
                $data = $value['placeholders'][$placeholder_name]['widgets'][$key];
                if(!$widget['_form']->isValid($data)){
                    $isVaild = false;
                }
            }
        }

        return $isVaild;
    }


}