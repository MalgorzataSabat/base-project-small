<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Language', 'base');

/**
 * Table_Language
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id_language
 * @property boolean $is_active
 * @property boolean $is_main
 * @property string  $code
 * @property string  $name
 * @property string  $domain
 * @property string  $locale
 * 
 * @method integer getId()
 * @method boolean isNew()
 * @method static Language find($id, $tableName = null)
 * @method static Language findOneBy*
 * @static Doctrine_Collection findBy*
 * 
 * @method static getIdLanguageById() getIdLanguageById($id)
 * @method getIdLanguage() getIdLanguage()
 * @method setIdLanguage() setIdLanguage($value)
 * @method static getIsActiveById() getIsActiveById($id)
 * @method getIsActive() getIsActive()
 * @method setIsActive() setIsActive($value)
 * @method static getIsMainById() getIsMainById($id)
 * @method getIsMain() getIsMain()
 * @method setIsMain() setIsMain($value)
 * @method static getCodeById() getCodeById($id)
 * @method getCode() getCode()
 * @method setCode() setCode($value)
 * @method static getNameById() getNameById($id)
 * @method getName() getName()
 * @method setName() setName($value)
 * @method static getDomainById() getDomainById($id)
 * @method getDomain() getDomain()
 * @method setDomain() setDomain($value)
 * @method static getLocaleById() getLocaleById($id)
 * @method getLocale() getLocale()
 * @method setLocale() setLocale($value)
 * 
 * @property Doctrine_Collection $LabelTranslations
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Table_Language extends Base_Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('language');
        $this->hasColumn('id_language', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'can_import' => true,
             ));
        $this->hasColumn('is_active', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             'can_import' => true,
             ));
        $this->hasColumn('is_main', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             'can_import' => true,
             ));
        $this->hasColumn('code', 'string ', 8, array(
             'type' => 'string ',
             'notnull' => true,
             'length' => '8',
             'can_import' => true,
             ));
        $this->hasColumn('name', 'string ', 255, array(
             'type' => 'string ',
             'notnull' => true,
             'length' => '255',
             'can_import' => true,
             ));
        $this->hasColumn('domain', 'string ', 255, array(
             'type' => 'string ',
             'notnull' => true,
             'length' => '255',
             'can_import' => true,
             ));
        $this->hasColumn('locale', 'string ', 255, array(
             'type' => 'string ',
             'length' => '255',
             'can_import' => true,
             ));

        $this->option('service', false);
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('LabelTranslation as LabelTranslations', array(
             'local' => 'id_language',
             'foreign' => 'id_language'));
    }
}