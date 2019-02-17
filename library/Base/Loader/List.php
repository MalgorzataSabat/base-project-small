<?php


class Base_Loader_List
{

    protected $_options = array();

    protected $_value = array();

    protected $_all = false;

    protected $_result = array();

    protected $_labels = array();

    /**
     * @var Base_Form_Filter
     */
    protected $_filter;

    /**
     * @param array $options
     */
    public function __construct($options = array())
    {
        if(isset($options['value'])){
            $value = array_filter($options['value']);
            $this->setValue($value);
        }

        if(isset($options['all'])){
            $this->setAll($options['all']);
        }

        if(!$this->_value && !$this->_all){
            throw new Exception('Wrong value data to export records');
        }

        $this->_options = $options;
    }

    /**
     * @return array
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->_value = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function getAll()
    {
        return $this->_all;
    }

    /**
     * @param $all
     * @return $this
     */
    public function setAll($all)
    {
        $this->_all = $all;

        return $this;
    }

    /**
     * @return array
     */
    public function getResult($select = null)
    {
        return $this->_result;
    }

    public function getLabels()
    {
        return $this->_labels;
    }

}