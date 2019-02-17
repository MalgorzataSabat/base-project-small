<?php

class Base_Validate_TimeAfter extends Zend_Validate_Abstract
{
    const FORMAT = 'time-after';

    protected $_messageTemplates = array(
        self::FORMAT  => "error_field_time-after",
    );

    protected $_fieldStart  = array('start');
    protected $_fieldEnd    = array('end');

    public function __construct($options = array())
    {
        if(isset($options['start'])){
            $this->_fieldStart = (array) $options['start'];
        }

        if(isset($options['end'])){
            $this->_fieldEnd = (array) $options['end'];
        }
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
        $start = '';
        foreach($this->_fieldStart as $v){
            $start.= trim(preg_replace('~\D~', '', $context[$v]));
        }

        $end = '';
        foreach($this->_fieldEnd as $v){
            $end.= trim(preg_replace('~\D~', '', $context[$v]));;
        }

        $start = strtotime($start);
        $end = strtotime($end);

        if($start > $end){
            $this->_error(self::FORMAT);

            return false;
        }


        return true;
    }
}
