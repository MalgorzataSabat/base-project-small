<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Meta', 'base');

/**
 * Table_Meta
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id_meta
 * @property string  $hash
 * @property integer $id_field
 * @property string $value
 * @property integer $id_service
 * 
 * @method integer getId()
 * @method boolean isNew()
 * @method static Meta find($id, $tableName = null)
 * @method static Meta findOneBy*
 * @static Doctrine_Collection findBy*
 * 
 * @method static getIdMetaById() getIdMetaById($id)
 * @method getIdMeta() getIdMeta()
 * @method setIdMeta() setIdMeta($value)
 * @method static getHashById() getHashById($id)
 * @method getHash() getHash()
 * @method setHash() setHash($value)
 * @method static getIdFieldById() getIdFieldById($id)
 * @method getIdField() getIdField()
 * @method setIdField() setIdField($value)
 * @method static getValueById() getValueById($id)
 * @method getValue() getValue()
 * @method setValue() setValue($value)
 * @method static getIdServiceById() getIdServiceById($id)
 * @method getIdService() getIdService()
 * @method setIdService() setIdService($value)
 * 
 * @property Field $Field
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Table_Meta extends Base_Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('meta');
        $this->hasColumn('id_meta', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('hash', 'string ', 32, array(
             'type' => 'string ',
             'length' => '32',
             ));
        $this->hasColumn('id_field', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('value', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('id_service', 'integer', null, array(
             'type' => 'integer',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Field', array(
             'local' => 'id_field',
             'foreign' => 'id_field',
             'onDelete' => 'CASCADE'));
    }
}