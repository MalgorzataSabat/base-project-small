<?php

class Base_Validate_Time extends Zend_Validate_Abstract
{
    const FORMAT = 'format';

    protected $_messageTemplates = array(
        self::FORMAT  => "error_field_time",
    );

    /**
     * Validate element value.
     *
     * @param  mixed $value
     * @param  mixed $context
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        $hasSecond = strlen($value) == 8 ? true : false;
        $pattern = $hasSecond ? '/^([0-9]{2}):([0-9]{2}):([0-9]{2})$/' : '/^([0-9]{2}):([0-9]{2})$/';

        if(!preg_match($pattern, $value)){
            $this->_error(self::FORMAT);

            return false;
        }else{
            $_v = explode(':', $value);

            if($_v[0] < 0 || $_v[0] > 23){
                $this->_error(self::FORMAT);

                return false;
            }

            if($_v[1] < 0 || $_v[1] > 59){
                $this->_error(self::FORMAT);

                return false;
            }

            if($hasSecond){
                if($_v[2] < 0 || $_v[2] > 59){
                    $this->_error(self::FORMAT);

                    return false;
                }
            }

        }


        return true;
    }
}
