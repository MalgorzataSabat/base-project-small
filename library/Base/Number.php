<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23.03.2018
 * Time: 13:29
 */

class Base_Number
{

    protected $_format = 'nr';

    protected $_options = array();

    protected $_formatKey = null;

    protected $_searchSql;

    public function __construct($options = array())
    {
        $this->_options = $options;

        if($this->_formatKey){
            $this->_format = ''; //Setting::getSetting($this->_formatKey);
        }
    }


    /**
     * @return null|string
     */
    public function getFormat()
    {
        return $this->_format;
    }

    /**
     * @param $format
     * @return $this
     */
    public function setFormat($format)
    {
        $this->_format = $format;

        return $this;
    }


    public function getNextNumber($options = array())
    {
        $format_quote =  preg_quote($this->_format, '/');

        $pregSearch = array(
            'yyyy' => date('Y'),
            'yy' => date('y'),
            'mm' => date('m'),
            'dd' => date('d'),
            'nr' => '([0-9]+)',
        );

        $this->_searchSql = '^'.str_replace(array_keys($pregSearch), $pregSearch, $format_quote).'$';

        $recordsList = $this->_getRecords();

        $pregNumber = '/'.$this->_searchSql.'/';
        $maxNumber = 0;
        foreach($recordsList as $v){
            preg_match($pregNumber, $v[$this->_colName], $number);

            if(isset($number[1])){
                $_number = (int) $number[1];
                if($_number > $maxNumber){ $maxNumber = $_number; }
            }
        }

        $numberSize = 0;
        $nextNumber = $maxNumber+1;

        if($numberSize){
            $nextNumber = str_pad($nextNumber, $numberSize, 0, STR_PAD_LEFT);
        }

        $pregSearch['nr'] = $nextNumber;
        $resultNumber = str_replace(array_keys($pregSearch), $pregSearch, $this->_format);

        return $resultNumber;
    }

    protected function _getRecords()
    {
        return array();
    }



}