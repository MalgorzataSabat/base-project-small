<?php

/**
 * LayoutTemplate
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class LayoutTemplate extends Table_LayoutTemplate
{
    /**
     * @param array $options
     * @return Doctrine_Query
     */
    public static function getQuery($options = array())
    {
        $options['id_service'] = null;

        $query = parent::getQuery($options);

        if(isset($options['is_default']) && strlen($options['is_default'])){
            $query->addWhere('o.is_default = ?', $options['is_default']);
        }

        if(isset($options['is_system']) && strlen($options['is_system'])){
            $query->addWhere('o.is_system = ?', $options['is_system']);
        }

        return $query;
    }

    /**
     * @param array $options
     * @return bool|LayoutTemplate
     * @throws Exception
     */
    public static function getDefaultRecord($options = array())
    {
        $options['is_default'] = '1';
        $options['hydrate'] = Doctrine::HYDRATE_RECORD;

        return self::getRecord($options);
    }

}