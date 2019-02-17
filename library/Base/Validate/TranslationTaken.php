<?php


class Base_Validate_TranslationTaken extends Zend_Validate_Abstract {

    const MATCH_FOUND = 'match';


    protected $_column = null;
    protected $_params = array();
    /**
     * Message template if not valid
     * @var array
     */
    protected $_messageTemplates = array(
        self::MATCH_FOUND => "%value% is already taken, please choose another."
    );

    /**
     * @param $options
     */
    public function __construct($options)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        } else if (!is_array($options)) {
            $options = func_get_args();
            $temp['column'] = array_shift($options);
            $temp['params'] = array_shift($options);

            if (!empty($options)) {
                $temp['encoding'] = array_shift($options);
            }

            $options = $temp;
        }

        $this->setColumn($options['column']);

        if(isset($options['params'])){
            $this->setParams($options['params']);
        }
    }

    protected function setColumn($column)
    {
        $this->_column = $column;
        return $this;
    }

    /**
     * @param array $params
     * @return $this
     */
    protected function setParams(array $params)
    {
        $this->_params = $params;
        return $this;
    }

    /**
     * Validate element value.
     * @param mixed $value
     * @param null $context
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        $valueCheck = $value;
        $this->_params[] = array($this->_column, '=', $valueCheck);

        $query = Doctrine_Query::create()
            ->from('Label o')
            ->innerJoin('o.Translations t WITH t.id_language = ?', $context['id_language']);
        $query->addWhere('type = ?', $context['type']);
        foreach ($this->_params as $param) {
            $query->addWhere($param[0] . ' ' . $param[1] . ' ?', $param[2]);
        }

        $result = $query->execute();

        if ( $result->count() > 0 ) {
            $this->_error(self::MATCH_FOUND, $value);
            return false;
        }

        return true;
    }
}