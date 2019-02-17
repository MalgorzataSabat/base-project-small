<?php

class Base_Query extends Doctrine_Query
{

    private $_addWhereType = 'addWhere';

    private $_defaultFieldType = array();

    public function addWhereByType($value, $field, $options = array())
    {
        $type = null;
        !$type && isset($value['type']) && $type = $value['type'];
        !$type && isset($this->_defaultFieldType[$field]) && $type = $this->_defaultFieldType[$field];
        !$type && $type = 'contain';

        if(is_array($value) && isset($value['value'])){
            $value = $value['value'];
        }


        if($type == 'empty')
        {
            $this->{$this->_addWhereType}('('.$field.' IS NULL OR '.$field.' = "")');
        }
        elseif(isset($value['min']) || isset($value['max']))
        {
            if ( isset($value['min']) && $value['min'] ) {
                $this->{$this->_addWhereType}($field . ' >= ?', $value['min']);
            }
            if ( isset($value['max']) && $value['max'] ) {
                $this->{$this->_addWhereType}($field . ' <= ?', $value['max']);
            }
        }
        elseif(strlen($value))
        {
            if($type == 'equal')
            {
                $this->{$this->_addWhereType}($field . ' = ?', $value);
            }
            elseif($type == 'diff')
            {
                $this->{$this->_addWhereType}($field.' != ?', $value);
            }
            elseif($type == 'not-contain' )
            {
                $this->{$this->_addWhereType}($field.' NOT LIKE ?', '%'.$value.'%');

            }elseif($type == 'start' )
            {
                $this->{$this->_addWhereType}($field.' LIKE ?', $value.'%');
            }
            elseif($type == 'end' )
            {
                $this->{$this->_addWhereType}($field.' LIKE ?', '%'.$value);
            }
            else
            { // contain
                $this->{$this->_addWhereType}($field . ' LIKE ?', '%'.$value.'%');
            }
        }


    }


    public function addWhereByDate($value, $field, $options = array())
    {
        if ( isset($value['min']) && $value['min'] ) {
            $this->{$this->_addWhereType}($field . ' >= ?', $value['min']);
        }
        if ( isset($value['max']) && $value['max'] ) {
            $this->{$this->_addWhereType}($field . ' <= ?', $value['max'].' 23:59:59');
        }
    }

    public function addWhereByTags($value, $field, $options = array())
    {
        $fieldValue = array_filter((array)$value, 'strlen');
        foreach($fieldValue as $v){
            $this->{$this->_addWhereType}('o.'.$field.' LIKE ?', '%"' . $v . '"%');
        }
    }

    public function addWhereByDictionary($value, $field, $options = array())
    {
        if(is_array($value) && isset($value['value'])){
            $type = isset($value['type']) ? $value['type'] : 'equal';
            $value = $value['value'];
        }else{
            $type = 'equal';
        }

        if($type == 'empty')
        {
            $this->{$this->_addWhereType}($field.' IS NULL OR ""');
        }
        elseif($type == 'open')
        {
            $ids = Dictionary::getListOpen($options['dictionary_object']);
            !$ids && $ids = array('0' => '0');
            $this->whereIn('o.'.$field, $ids);
        }
        elseif($type == 'close')
        {
            $ids = Dictionary::getListClose($options['dictionary_object']);
            !$ids && $ids = array('0' => '0');
            $this->whereIn('o.' . $field, $ids);
        }
        elseif($type == 'equal')
        {
            if ( isset($value['min']) && $value['min'] ) {
                $this->{$this->_addWhereType}($field . ' >= ?', $value['min']);
            }
            if ( isset($value['max']) && $value['max'] ) {
                $this->{$this->_addWhereType}($field . ' <= ?', $value['max']);
            }
            if(!is_array($value) && strlen($value))
            {
                $this->{$this->_addWhereType}($field.' = ?', $value);
            }
        }
        elseif($type == 'diff') {
            $this->{$this->_addWhereType}($field.' != ?', $value);
        }
    }




    public function addHavingByType($value, $field, $options = array())
    {
        $this->_addWhereType = 'addHaving';
        $this->addWhereByType($value, $field, $options);
    }

    /**
     * @param array $defaultFieldType
     * @return $this
     */
    public function setDefaultWhereType($defaultFieldType = array())
    {
        $this->_defaultFieldType = $defaultFieldType;

        return $this;
    }

}
