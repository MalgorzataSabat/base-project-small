<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Marek Skotarek
 * Date: 14.07.2013
 * Time: 21:16
 * To change this template use File | Settings | File Templates.
 */

class User_Validate_NewEmail extends Zend_Validate_Abstract {

    const NEW_EMAIL = 'new_email';
    const NEW_EMAIL_OWN_NOT_ACTIVE = 'new_email_own_not_active';
    const NEW_EMAIL_OTHER_USER = 'new_email_other_user';

    /**
     * @var Doctrine_Record
     */
    protected $_model = null;

    /**
     * @var string
     */
    protected $_column = null;

    /**
     * Params to check.
     *
     * @var string
     */
    protected $_params = array();

    protected $_messageTemplates = array(
        self::NEW_EMAIL_OWN_NOT_ACTIVE  => "'%value%' is already taken by you but not active, please check your email.",
        self::NEW_EMAIL_OTHER_USER  => "'%value%' is already taken, please choose another.",
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
     * @return User_Validate_NewEmail
     */
    public function setParams(array $params)
    {
        $this->_params = $params;
        return $this;
    }

    private function getUserConfirmRequest()
    {
        $query = UserConfirmRequest::getQuery(
            array(
                'type' => UserConfirmRequest::TYPE_NEW_EMAIL,
                'id_user' => Base_Auth::getUser('id_user')
            )
        );
        $userConfirmRequest = $query->fetchOne();

        return $userConfirmRequest;
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
        $userConfirmRequest = $this->getUserConfirmRequest();

        $query = Doctrine_Query::create()->from( $this->_model );
        foreach( $this->_params as $param ){
            $query->addWhere( $param[0] .' '.$param[1].' ?', $param[2] );
        }

        $result = $query->fetchOne();

        if ( $result ) {
            if ( $result->getIdUser() === Base_Auth::getUser('id_user') && false === $result->getIsActive() ) {
                if ( $userConfirmRequest && !(bool) $userConfirmRequest->is_active ) {
                    return true;
                } else {
                    $this->_error(self::NEW_EMAIL_OWN_NOT_ACTIVE, $value);
                    return false;
                }
            } else {
                $this->_error(self::NEW_EMAIL_OTHER_USER, $value);
                return false;
            }
        }

        return true;
    }

    /**
     * Merge params.
     *
     * @param array $params
     * @return User_Validate_NewEmail
     */
    public function mergeParams(array $params)
    {
        $this->_params = array_merge($this->_params, $params);
        return $this->_params;
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