<?php


class Layout_Extractor extends Base_Extractor
{

    protected $_tables = array('layout', 'layout_template');

    protected $_data = array('layout', 'layout_template');

    protected $_module = 'layout';

    protected $_dumpResource = array('layout');

    protected $_dumpLabel = array('layout');

    protected $_dumpChangelog = array('layout');

    protected $_dumpSettings = array('layout');

    protected $_dumpRule = array('layout');

}