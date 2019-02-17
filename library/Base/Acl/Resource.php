<?php

/**
 * @see Zend_Acl_Resource_Interface
 */
require_once 'Zend/Acl/Resource/Interface.php';


/**
 * @category   Zend
 * @package    Zend_Acl
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Base_Acl_Resource implements Zend_Acl_Resource_Interface
{
    /**
     * Unique id of Resource
     *
     * @var string
     */
    protected $_resourceId;

    /**
     * @param $resourceId
     * @param $model
     */
    public function __construct($resourceId, $model = null)
    {
        if(null === $model){
            $model = $this->loadModelFromRequest();
        }

        $this->_resourceId = (string) $resourceId;
        if($model){
            foreach($model as $k => $v){
                $this->$k = $v;
            }
        }
    }

    /**
     * Defined by Zend_Acl_Resource_Interface; returns the Resource identifier
     *
     * @return string
     */
    public function getResourceId()
    {
        return $this->_resourceId;
    }

    /**
     * Defined by Zend_Acl_Resource_Interface; returns the Resource identifier
     * Proxies to getResourceId()
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getResourceId();
    }


    protected function loadModelFromRequest()
    {
        return null;
    }
}
