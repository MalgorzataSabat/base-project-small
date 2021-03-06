<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Setting', 'base');

/**
 * Table_Setting
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id_setting
 * @property string  $key
 * @property string $value
 * @property integer $id_service
 * 
 * @method integer getId()
 * @method boolean isNew()
 * @method static Setting find($id, $tableName = null)
 * @method static Setting findOneBy*
 * @static Doctrine_Collection findBy*
 * 
 * @method static getIdSettingById() getIdSettingById($id)
 * @method getIdSetting() getIdSetting()
 * @method setIdSetting() setIdSetting($value)
 * @method static getKeyById() getKeyById($id)
 * @method getKey() getKey()
 * @method setKey() setKey($value)
 * @method static getValueById() getValueById($id)
 * @method getValue() getValue()
 * @method setValue() setValue($value)
 * @method static getIdServiceById() getIdServiceById($id)
 * @method getIdService() getIdService()
 * @method setIdService() setIdService($value)
 * 
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Table_Setting extends Base_Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('setting');
        $this->hasColumn('id_setting', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'can_import' => true,
             ));
        $this->hasColumn('key', 'string ', 255, array(
             'type' => 'string ',
             'length' => '255',
             'can_import' => true,
             ));
        $this->hasColumn('value', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'can_import' => true,
             ));
        $this->hasColumn('id_service', 'integer', null, array(
             'type' => 'integer',
             'can_import' => false,
             'log' => false,
             ));


        $this->setAttribute(Doctrine_Core::ATTR_COLL_KEY, 'key');
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}