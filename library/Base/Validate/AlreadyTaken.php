<?php


class Base_Validate_AlreadyTaken extends Zend_Validate_Abstract
{
    const MATCH_FOUND = 'match';

    /**
     * @var Doctrine_Record
     */
    protected $_model = null;

    /**
     * @var string
     */
    protected $_column = null;

    /**
     * @var Doctrine_Query
     */
    protected $_query = null;

    /**
     * Params to check.
     *
     * @var string
     */
    protected $_params = array();

    protected $_messageTemplates = array(
         self::MATCH_FOUND  => "'%value%' is already taken, please choose another.",
    );

    /**
     * @param $options|Zend_Config|array
     *
     * @params order model, column, params
     * @params array('model' => '', 'column' => '', 'params' => array())
     *
     */
    public function __construct($options)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        } else if (!is_array($options)) {
            $options = func_get_args();
            $temp['model'] = array_shift($options);
            $temp['column'] = array_shift($options);
            $temp['params'] = array_shift($options);
            $temp['query'] = array_shift($options);

            if (!empty($options)) {
                $temp['encoding'] = array_shift($options);
            }

            $options = $temp;
        }

        $this->setModel($options['model']);
        $this->setColumn($options['column']);

        if(isset($options['params'])){
            $this->setParams($options['params']);
        }

        if(isset($options['query'])){
            $this->setQuery($options['query']);
        }
    }

    /**
     * @param $model
     * @return $this
     */
    public function setModel( $model )
    {
        $this->_model = $model;
        return $this;
    }

    /**
     * @param $column
     * @return $this
     */
    public function setColumn( $column )
    {
        $this->_column = $column;
        return $this;
    }

    /**
     * Set params to check a value.
     *
     * @param array $params
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->_params = $params;
        return $this;
    }

    /**
     * Set query instanceof Doctrine_Query.
     *
     * @param Doctrine_Query $query
     * @return $this
     */
    public function setQuery($query)
    {
        if($query instanceof Doctrine_Query){
            $this->_query = $query;
        }

        return $this;
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
        $valueCheck = $value;
        $this->_params[] = array( $this->_column, '=', $valueCheck );

        if(!$this->_query){
            $this->_query = Doctrine_Query::create()
                ->from( $this->_model );

            $table = Doctrine::getTable($this->_model);
            if($table->hasColumn('id_service')){
                $this->_query->addWhere('id_service = ?', Base_Service::getId());
            }

            if($table->hasColumn('archived_at')){
                $this->_query->addWhere('archived_at IS NULL');
            }

        }

        foreach( $this->_params as $param ){
            $this->_query->addWhere( $param[0] .' '.$param[1].' ?', $param[2] );
        }

        $result = $this->_query->execute();

        if( $result->count() > 0 ){
            $this->_error(self::MATCH_FOUND, $value);
            return false;
        }

        return true;
    }

    /**
     * Merge params.
     *
     * @param array $params
     * @return Base_Validate_AlreadyTaken
     */
    public function mergeParams(array $params)
    {
        $this->_params = array_merge($this->_params, $params);
        return $this;
    }

    /**
     * Retrieve params.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

}