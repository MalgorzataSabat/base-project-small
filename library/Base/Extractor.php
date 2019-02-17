<?php

class Base_Extractor
{

    /**
     * Ściezka do eksportowanego pliku
     */
    protected $_filename = null;

    /**
     * Aktualnie eksportowany moduł
     */
    protected $_module = null;

    /**
     * Dane konfiguracyjne bazy danych
     */
    protected $_dbConfig = null;

    /**
     * Komenta mysqldump
     */
    protected $_mysqlCommand = null;

    /**
     * takie tabele mają być wydzielone // tylko struktura
     */
    protected $_tables = array();

    /**
     * Z jakich tabel dane mają być wydzielone
     */
    protected $_data = array();

    /**
     * Jakie prefixy zasobów są wydzielane
     */
    protected $_dumpResource = array();

    /**
     * Jakie dane reguły uprawnień są wydzielane
     */
    protected $_dumpRule = array();

    /**
     * Jakie moduly labeli są wydzielane
     */
    protected $_dumpLabel = array();

    /**
     * Jakie moduly labeli są wydzielane
     */
    protected $_dumpChangelog = array();

    /**
     * Jakie prefixy ustawień są wydzielane
     */
    protected $_dumpSettings = array();

    /**
     * Jakie dane słownikowe są wydzielane
     */
    protected $_dumpDictionary = array();




    public function __construct()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $this->_filename = $frontController->getModuleDirectory($this->_module);

        $dbConfig = include ROOT_PATH.'/application/config/database.php';
        preg_match('/mysql:\/\/(.*):(.*)@localhost\/(.*)/', $dbConfig['connection'], $dbPreg);

        $dbConfig = array(
            'name' => $dbPreg[3],
            'user' => $dbPreg[1],
            'pass' => $dbPreg[2]
        );

        $this->_filename.= DS.'data';
        Base::createDir($this->_filename);
        $this->_filename.= DS.'migration';
        Base::createDir($this->_filename);

        $this->_filename.= DS.'_install.sql';
        $this->_mysqlCommand = 'mysqldump '.$dbConfig['name'].' ';
    }

    public function extract()
    {
        file_put_contents($this->_filename, '');

        $this->preDump();
        $this->dumpTables();
        $this->dumpDictionary();
        $this->dumpData();
        $this->dumpResource();
        $this->dumpRule();
        $this->dumpChangelog();
        $this->dumpLabel();
        $this->dumpSettings();
        $this->postDump();
    }

    protected function preDump(){}

    protected function dumpTables()
    {
        if(!$this->_tables) return;

        $command = $this->_mysqlCommand.' --no-data '.join(' ',$this->_tables) .' >> '.$this->_filename;
        exec($command);
    }

    protected function dumpDictionary()
    {
        foreach($this->_dumpDictionary as $object){
            $dictionaryList = Doctrine_Query::create()
                ->from('Dictionary o')
                ->where('o.object = ?', $object)
                ->execute(array(), Doctrine::HYDRATE_ARRAY);

            $handle = fopen($this->_filename, 'a');
            fwrite($handle, "-- Dump OBJECT: ".$object."\r\n");

            foreach($dictionaryList as $v){
                $sqlLabel = "INSERT INTO `dictionary` (`object`, `name`, `order`, `is_closed`, `is_default`, `class`, `style`, `data`) VALUES ";
                $sqlLabel.= "('".addslashes($v['object'])."', '".addslashes($v['name'])."', '".(int)$v['order']."', '".(int)$v['is_closed']."', '".(int)$v['is_default']."','".addslashes($v['class'])."', '".addslashes($v['style'])."', '".addslashes($v['data'])."');";
                fwrite($handle, $sqlLabel."\r\n");
            }

            fclose($handle);
        }
    }




    protected function dumpData()
    {
        if(!$this->_data) return;

        $command = $this->_mysqlCommand.' --no-create-info '.join(' ',$this->_data) .' >> '.$this->_filename;
        exec($command);
    }

    protected function dumpResource()
    {
        $parentIds = array();

        foreach($this->_dumpResource as $module){
            $resourceList = Doctrine_Query::create()
                ->from('AclResource o')
                ->leftJoin('o.Resource r')
                ->where('o.name LIKE ?', $module.'%')
                ->execute(array(), Doctrine::HYDRATE_ARRAY);

            $handle = fopen($this->_filename, 'a');

            foreach($resourceList as $resource){
                $sqlParent = isset($parentIds[$resource['id_parent']]) ? '@resParentId'.$resource['id_parent'] : 'NULL';

                $sqlLabel = "INSERT INTO `acl_resource` (`id_parent`, `name`, `text`, `desc`) VALUES ";
                $sqlLabel.= "(".$sqlParent.",'".addslashes($resource['name'])."','".addslashes($resource['text'])."','".addslashes($resource['desc'])."');";
                fwrite($handle, $sqlLabel."\r\n");

                $parentIds[$resource['id_acl_resource']] = $resource['id_acl_resource'];
                $sqlSet = 'SET @resParentId'.$resource['id_acl_resource'].' = LAST_INSERT_ID();';
                fwrite($handle, $sqlSet."\r\n");

                if(count($resource['Resource'])){
                    foreach($resource['Resource'] as $rItem){
                        $sqlItem = 'INSERT INTO `acl_resource_item`(`id_acl_resource`, `name`) VALUES ';
                        $sqlItem.= "(@resParentId".$resource['id_acl_resource'].",'".addslashes($rItem['name'])."');";
                        fwrite($handle, $sqlItem."\r\n");
                    }
                }
            }

            fclose($handle);
        }
    }


    protected function dumpRule()
    {
        foreach($this->_dumpRule as $module){
            $ruleList = Doctrine_Query::create()
                ->from('AclRule o')
                ->where('o.resource LIKE ?', $module.'%')
                ->execute(array(), Doctrine::HYDRATE_ARRAY);

            $handle = fopen($this->_filename, 'a');
            fwrite($handle, "-- Dump MODULE: ".$module."\r\n");

            foreach($ruleList as $rule){
                $sqlLabel = "INSERT INTO `acl_rule`(`is_allow`, `role`, `resource`) VALUES ";
                $sqlLabel.= "('".(int)$rule['is_allow']."', '".$rule['role']."', '".$rule['resource']."');";
                fwrite($handle, $sqlLabel."\r\n");
            }

            fclose($handle);
        }
    }

    protected function dumpChangelog()
    {
        foreach($this->_dumpChangelog as $module){
            $command = $this->_mysqlCommand.' --no-create-info database_changelog --where="module = \''.$module.'\'" >> '.$this->_filename;
            exec($command);
        }
    }

    protected function dumpLabel()
    {
        foreach($this->_dumpLabel as $module){
            $labelList = Doctrine_Query::create()
                ->from('Label o')
                ->leftJoin('o.Translations t')
                ->where('o.module = ?', $module)
                ->execute(array(), Doctrine::HYDRATE_ARRAY);

            $handle = fopen($this->_filename, 'a');

            foreach($labelList as $label){
                $sqlLabel = "INSERT INTO `label`(`label`, `type`, `created_at`, `modified_at`, `module`, `from_import`) VALUES ";
                $sqlLabel.= "('".addslashes($label['label'])."','".$label['type']."','".$label['created_at']."','".$label['modified_at']."','".$label['module']."','".(int)$label['from_import']."');";
                fwrite($handle, $sqlLabel."\r\n");

                if(count($label['Translations'])){
                    $sqlTr = 'INSERT INTO `label_translation`(`id_label`, `id_language`, `value`) VALUES ';
                    $sqlTrItems = array();
                    foreach($label['Translations'] as $tr){
                        $sqlTrItems[] = "(LAST_INSERT_ID(),'".$tr['id_language']."','".addslashes($tr['value'])."')";

                    }

                    $sqlTr = $sqlTr. join(',', $sqlTrItems).';';
                    fwrite($handle, $sqlTr."\r\n");
                }
            }

            fclose($handle);
        }
    }

    protected function dumpSettings()
    {
        foreach($this->_dumpSettings as $module){
            $settingList = Doctrine_Query::create()
                ->from('Setting o')
                ->where('o.key LIKE ?', $module.'%')
                ->execute(array(), Doctrine::HYDRATE_ARRAY);

            $handle = fopen($this->_filename, 'a');

            foreach($settingList as $setting){
                $sqlLabel = "INSERT INTO `setting` (`key`, `value`) VALUES ";
                $sqlLabel.= "('".addslashes($setting['key'])."', '".addslashes($setting['value'])."');";
                fwrite($handle, $sqlLabel."\r\n");
            }

            fclose($handle);
        }
    }

    protected function postDump(){}

}