<?php


class Base_Extractor_Default extends Base_Extractor
{

    protected $_tables = array('acl_resource', 'acl_resource_item', 'acl_role', 'acl_rule', 'cron', 'database_changelog',
        'email_send image', 'label', 'label_translation', 'language', 'query', 'session', 'setting');

    protected $_data = array('acl_role', 'cron', 'language');

    protected $_module = 'default';

    protected $_dumpResource = array('default', 'admin', 'auth', 'image');

    protected $_dumpLabel = array('default', 'admin', 'auth', 'image', '');

    protected $_dumpChangelog = array('default', 'admin', 'auth', 'image', '');

    protected $_dumpSettings = array('email', 'meta', 'system', 'template', 'auth');

    protected $_dumpDictionary = array();

    protected $_dumpRule = array('default', 'admin', 'auth', 'image');

}