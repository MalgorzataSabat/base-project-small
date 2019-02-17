<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Marek Skotarek
 * Date: 30.06.2013
 * Time: 02:50
 * To change this template use File | Settings | File Templates.
 */

class Base_Validate_RecordExists extends Zend_Validate_Abstract
{
    const RECORD_EXISTS = 'record_exists';

    /**
     * @var Doctrine_Record
     */
    protected $_model;

    /**
     * @var string
     */
    protected $_column;

    protected $_messageTemplates = array(
        self::RECORD_EXISTS => 'no record exists'
    );

    public function __construct($options)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        } else if (!is_array($options)) {
            $options = func_get_args();
            $temp['model'] = array_shift($options);
            $temp['column'] = array_shift($options);
//            $temp['params'] = array_shift($options);

            if (!empty($options)) {
                $temp['encoding'] = array_shift($options);
            }

            $options = $temp;
        }

        $this->setModel($options['model']);
        $this->setColumn($options['column']);

//        if(isset($options['params'])){
//            $this->setParams($options['params']);
//        }
    }

    /**
     * @param $model
     * @return $this
     */
    protected function setModel($model) {
        $this->_model = $model;
        return $this;
    }

    /**
     * @param $column
     * @return $this
     */
    protected function setColumn($column) {
        $this->_column = $column;
        return $this;
    }

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value
     * @return boolean
     * @throws Zend_Validate_Exception If validation of $value is impossible
     */
    public function isValid($value)
    {
        $query = Doctrine_Query::create()->from( $this->_model )
            ->addWhere( $this->_column . '= ?', $value);

        $result = $query->execute();

        if ( $result->count() > 0 ) {
            return true;
        } else {
            $this->_error(self::RECORD_EXISTS, $value);
            return false;
        }
    }
}