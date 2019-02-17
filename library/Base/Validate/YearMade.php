<?php


class Base_Validate_YearMade extends Zend_Validate_Abstract
{
    /**
     * Validation failure message key for when the value fails the mod  checksum
     */
    const RANGE = 'wrongYearMade_range';

    const DIGITS = 'wrongYearMade_digits';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::DIGITS   => "form-error_year-made_digits",
        self::RANGE   => "form-error_year-made_range",
    );

    private $_min = 0;

    private $_max = 0;


    public function __construct($options = array())
    {
        if(isset($options['min'])){
            $this->setMin($options['min']);
        }else{
            $this->setMin(1970);
        }

        if(isset($options['max'])){
            $this->setMax($options['max']);
        }else{
            $this->setMax(date('Y'));
        }

    }


    public function setMin($year)
    {
        $this->_min = $year;
    }


    public function setMax($year)
    {
        $this->_max = $year;
    }

    /**
     * Defined by Zend_Validate_Interface
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $validatorDigits = new Zend_Validate_Digits();

        if(!$validatorDigits->isValid($value)){
            $this->_error(self::DIGITS);
            return false;
        }

        if($value < $this->_min || $value > $this->_max){
            $this->_error(self::RANGE);
            return false;
        }

        return true;
    }
}