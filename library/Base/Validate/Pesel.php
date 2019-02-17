<?php

class Base_Validate_Pesel extends Zend_Validate_Abstract
{
    /**
    * Validation failure message key for when the value is not of valid length
    */
    const LENGTH   = 'numLength';

    /**
    * Validation failure message key for when the value fails the mod  checksum
    */
    const CHECKSUM = 'numChecksum';


    /**
    * Digits filter for input
    *
    * @var Zend_Filter_Digits
    */
    protected static $_filter = null;


    /**
    * @var array
    */
    protected $_messageTemplates = array(
    self::LENGTH   => "'%value%' must contain 11 digits",
    self::CHECKSUM => "Luhn algorithm (mod-10 checksum) failed on '%value%'"
    );

    /**
    * Defined by Zend_Validate_Interface
    *
    * Returns true if and only if $value contains a valid Eividencial namber message
    *
    * @param  string $value
    * @return boolean
    */
    public function isValid($value)
    {

    $this->_setValue($value);

    if (null === self::$_filter) {
    /**
    * @see Zend_Filter_Digits
    */
    require_once 'Zend/Filter/Digits.php';
    self::$_filter = new Zend_Filter_Digits();
    }

    $valueFiltered = self::$_filter->filter($value);

    $length = strlen($valueFiltered);

    if ($length != 11) {
    $this->_error(self::LENGTH);
    return false;
    }

    $mod = 10;
    $sum = 0;
    $weights = array (1, 3, 7, 9, 1, 3, 7, 9, 1, 3);

    preg_match_all("/\d/", $valueFiltered, $digits) ;

    $valueFilteredArray = $digits[0];


    foreach ( $valueFilteredArray as $digit )
    {
    $weight = current($weights);
    $sum += $digit * $weight;
    next($weights);
    }

    if ( (((10 - ($sum % $mod) == 10) ? 0 : 10) - ($sum % $mod)) != $valueFilteredArray[$length - 1] )
    {
    $this->_error(self::CHECKSUM, $valueFiltered);
    return false;
    }

    return true;
    }
}