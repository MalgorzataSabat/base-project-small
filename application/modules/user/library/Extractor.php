<?php


class User_Extractor extends Base_Extractor
{

    protected $_tables = array('user', 'user_confirm_request', 'user_email');

    protected $_data = array();

    protected $_module = 'user';

    protected $_dumpResource = array('user');

    protected $_dumpLabel = array('user');

    protected $_dumpChangelog = array('user');

    protected $_dumpSettings = array('user');

    protected $_dumpDictionary = array();

    protected $_dumpRule = array('user');


    protected function dumpData()
    {
        $command = $this->_mysqlCommand.' --no-create-info user --where="role LIKE \'%%admin%%\' OR role LIKE \'%%developer%%\'" >> '.$this->_filename;
        exec($command);

        $command = $this->_mysqlCommand.' --lock-all-tables --no-create-info user_email --where="id_user IN (SELECT id_user FROM user WHERE role LIKE \'%%admin%%\' OR role LIKE \'%%developer%%\')" >> '.$this->_filename;
        exec($command);
    }

}