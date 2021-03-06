<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('EmailSend', 'base');

/**
 * Table_EmailSend
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id_email_send
 * @property timestamp $created_at
 * @property boolean $is_sent
 * @property boolean $to_send
 * @property string $subject
 * @property string $from
 * @property string $to
 * @property datetime $sent_time
 * @property string $filename
 * @property string $exception
 * @property integer $id_service
 * 
 * @method integer getId()
 * @method boolean isNew()
 * @method static Email_send find($id, $tableName = null)
 * @method static Email_send findOneBy*
 * @static Doctrine_Collection findBy*
 * 
 * @method static getIdEmailSendById() getIdEmailSendById($id)
 * @method getIdEmailSend() getIdEmailSend()
 * @method setIdEmailSend() setIdEmailSend($value)
 * @method static getCreatedAtById() getCreatedAtById($id)
 * @method getCreatedAt() getCreatedAt()
 * @method setCreatedAt() setCreatedAt($value)
 * @method static getIsSentById() getIsSentById($id)
 * @method getIsSent() getIsSent()
 * @method setIsSent() setIsSent($value)
 * @method static getToSendById() getToSendById($id)
 * @method getToSend() getToSend()
 * @method setToSend() setToSend($value)
 * @method static getSubjectById() getSubjectById($id)
 * @method getSubject() getSubject()
 * @method setSubject() setSubject($value)
 * @method static getFromById() getFromById($id)
 * @method getFrom() getFrom()
 * @method setFrom() setFrom($value)
 * @method static getToById() getToById($id)
 * @method getTo() getTo()
 * @method setTo() setTo($value)
 * @method static getSentTimeById() getSentTimeById($id)
 * @method getSentTime() getSentTime()
 * @method setSentTime() setSentTime($value)
 * @method static getFilenameById() getFilenameById($id)
 * @method getFilename() getFilename()
 * @method setFilename() setFilename($value)
 * @method static getExceptionById() getExceptionById($id)
 * @method getException() getException()
 * @method setException() setException($value)
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
abstract class Table_EmailSend extends Base_Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('email_send');
        $this->hasColumn('id_email_send', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'can_import' => true,
             ));
        $this->hasColumn('created_at', 'timestamp', null, array(
             'type' => 'timestamp',
             'notnull' => true,
             'can_import' => true,
             ));
        $this->hasColumn('is_sent', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             'can_import' => true,
             ));
        $this->hasColumn('to_send', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             'can_import' => true,
             ));
        $this->hasColumn('subject', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'can_import' => true,
             ));
        $this->hasColumn('from', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'can_import' => true,
             ));
        $this->hasColumn('to', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'can_import' => true,
             ));
        $this->hasColumn('sent_time', 'datetime', null, array(
             'type' => 'datetime',
             'notnull' => false,
             'can_import' => true,
             ));
        $this->hasColumn('filename', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'can_import' => true,
             ));
        $this->hasColumn('exception', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'can_import' => true,
             ));
        $this->hasColumn('id_service', 'integer', null, array(
             'type' => 'integer',
             'can_import' => false,
             'log' => false,
             ));

        $this->option('select', 'o.*');
        $this->option('search', 'o.subject');
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}