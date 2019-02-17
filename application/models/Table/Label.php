<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Label', 'base');

/**
 * Table_Label
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id_label
 * @property string  $label
 * @property string  $type
 * @property timestamp $created_at
 * @property timestamp $modified_at
 * @property string  $module
 * @property boolean $from_import
 * 
 * @method integer getId()
 * @method boolean isNew()
 * @method static Label find($id, $tableName = null)
 * @method static Label findOneBy*
 * @static Doctrine_Collection findBy*
 * 
 * @method static getIdLabelById() getIdLabelById($id)
 * @method getIdLabel() getIdLabel()
 * @method setIdLabel() setIdLabel($value)
 * @method static getLabelById() getLabelById($id)
 * @method getLabel() getLabel()
 * @method setLabel() setLabel($value)
 * @method static getTypeById() getTypeById($id)
 * @method getType() getType()
 * @method setType() setType($value)
 * @method static getCreatedAtById() getCreatedAtById($id)
 * @method getCreatedAt() getCreatedAt()
 * @method setCreatedAt() setCreatedAt($value)
 * @method static getModifiedAtById() getModifiedAtById($id)
 * @method getModifiedAt() getModifiedAt()
 * @method setModifiedAt() setModifiedAt($value)
 * @method static getModuleById() getModuleById($id)
 * @method getModule() getModule()
 * @method setModule() setModule($value)
 * @method static getFromImportById() getFromImportById($id)
 * @method getFromImport() getFromImport()
 * @method setFromImport() setFromImport($value)
 * 
 * @property Doctrine_Collection $Translations
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Table_Label extends Base_Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('label');
        $this->hasColumn('id_label', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'can_import' => true,
             ));
        $this->hasColumn('label', 'string ', 255, array(
             'type' => 'string ',
             'notnull' => true,
             'length' => '255',
             'can_import' => true,
             ));
        $this->hasColumn('type', 'string ', 32, array(
             'type' => 'string ',
             'notnull' => true,
             'default' => 'label',
             'length' => '32',
             'can_import' => true,
             ));
        $this->hasColumn('created_at', 'timestamp', null, array(
             'type' => 'timestamp',
             'notnull' => true,
             'can_import' => true,
             ));
        $this->hasColumn('modified_at', 'timestamp', null, array(
             'type' => 'timestamp',
             'notnull' => true,
             'can_import' => true,
             ));
        $this->hasColumn('module', 'string ', 255, array(
             'type' => 'string ',
             'notnull' => true,
             'length' => '255',
             'can_import' => true,
             ));
        $this->hasColumn('from_import', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             'can_import' => true,
             ));

        $this->option('service', false);
        $this->option('select', 'o.*, t.id_language as id_language, t.value as value, o.module as module, o.created_at as created_at, o.modified_at as modified_at');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('LabelTranslation as Translations', array(
             'local' => 'id_label',
             'foreign' => 'id_label'));
    }
}