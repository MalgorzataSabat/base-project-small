<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Session', 'base');

/**
 * Table_Session
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $modified
 * @property integer $lifetime
 * @property string $data
 * @property integer $id_user
 * @property string  $ip
 * @property string $agent
 * @property string $address
 * 
 * @method integer getId()
 * @method boolean isNew()
 * @method static Session find($id, $tableName = null)
 * @method static Session findOneBy*
 * @static Doctrine_Collection findBy*
 * 
 * @method static getIdById() getIdById($id)
 * @method getId() getId()
 * @method setId() setId($value)
 * @method static getModifiedById() getModifiedById($id)
 * @method getModified() getModified()
 * @method setModified() setModified($value)
 * @method static getLifetimeById() getLifetimeById($id)
 * @method getLifetime() getLifetime()
 * @method setLifetime() setLifetime($value)
 * @method static getDataById() getDataById($id)
 * @method getData() getData()
 * @method setData() setData($value)
 * @method static getIdUserById() getIdUserById($id)
 * @method getIdUser() getIdUser()
 * @method setIdUser() setIdUser($value)
 * @method static getIpById() getIpById($id)
 * @method getIp() getIp()
 * @method setIp() setIp($value)
 * @method static getAgentById() getAgentById($id)
 * @method getAgent() getAgent()
 * @method setAgent() setAgent($value)
 * @method static getAddressById() getAddressById($id)
 * @method getAddress() getAddress()
 * @method setAddress() setAddress($value)
 * 
 * @property User $User
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Table_Session extends Base_Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('session');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'can_import' => true,
             ));
        $this->hasColumn('modified', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'can_import' => true,
             ));
        $this->hasColumn('lifetime', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'can_import' => true,
             ));
        $this->hasColumn('data', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'can_import' => true,
             ));
        $this->hasColumn('id_user', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             'can_import' => true,
             ));
        $this->hasColumn('ip', 'string ', 16, array(
             'type' => 'string ',
             'notnull' => true,
             'length' => '16',
             'can_import' => true,
             ));
        $this->hasColumn('agent', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'can_import' => true,
             ));
        $this->hasColumn('address', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'can_import' => true,
             ));

        $this->option('service', false);
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('User', array(
             'local' => 'id_user',
             'foreign' => 'id_user',
             'onDelete' => 'CASCADE'));
    }
}