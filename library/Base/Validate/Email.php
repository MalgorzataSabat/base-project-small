<?php

class Base_Validate_Email extends Zend_Validate_Abstract
{
    /**
    * Validation failure message key for when the value fails the mod  checksum
    */
    const FORMAT = 'format';

    private $_regexp_email = "/^([[:alnum:]]([[:alnum:]_\.\+:-])?)+@([[:alnum:]][[:alnum:]_\.:-]+[[:alnum:]]\.)?((([[:alnum:]]([[:alnum:]_\.:-]{1,59})?[[:alnum:]])|[[:alnum:]])\.)([a-z]{2,4}|[0-9]{1,3})$/i";

    private $_separator = null;

    public function __construct($options = array())
    {
        if(isset($options['separator'])){
            $this->setSeparator($options['separator']);
        }

    }

    /**
     * @param $separator
     * @return $this
     */
    public function setSeparator($separator)
    {
        $this->_separator = $separator;

        return $this;
    }

    /**
    * @var array
    */
    protected $_messageTemplates = array(
        self::FORMAT   => "form-error_email-address",
    );

    public function isValid($value)
    {
        if($this->_separator){
            $value = array_map('trim',explode($this->_separator, $value));
        }else{
            $value = (array) $value;
        }


        foreach($value as $email){
            if(!preg_match( $this->_regexp_email,$email)) {

                $this->_error(self::FORMAT);
                return false;
            }
        }

        return true;
    }
}