<?php

class Base_Tool_Project_Provider_Migration extends Zend_Tool_Project_Provider_Abstract
{
	static $msg_counter = 0;
	private $timer = 0;

	/**
	 * @var Zend_Application
	 */
	private $app;

    private $_files = array();
    private $_database = array();
    private $_missing_migration = array();
    private $_missing_files = array();

    /**
     * @var Zend_Controller_Front
     */
    private $_frontController;


	public function __construct()
	{
		$this->_resetTimer();
		$this->app = Zend_Registry::get('application');
        $this->app->getBootstrap()->bootstrap('autoload');
        $this->app->getBootstrap()->bootstrap('cache');
        $this->app->getBootstrap()->bootstrap('modules');
        $this->app->getBootstrap()->bootstrap('database');

        $this->_frontController = Zend_Controller_Front::getInstance();
	}


    public function diff()
	{
		$this->_print("\f Migration diff:", false);
		$this->_print('Provider init');

        $this->getMigrationDiff();


        $count_files = array();
        $last_files = array();
        foreach($this->_files as $modules => $files){
            $count_files[] = $modules.':'.count($files);
            $last_files[] = $modules.':'.end($files);
        }

        $this->_print('Load migration files: '.join('; ',$count_files));
        if(count($last_files) > 0){
            $this->_print('Last migration file : '.join('; ',$last_files));
        }

        $count_db = array();
        $last_db = array();
        foreach($this->_database as $modules => $files){
            $count_db[] = $modules.':'.count($files);
            $last_db[] = $modules.':'.end($files);
        }

        $this->_print('Load database changelog : '.join('; ',$count_db));
        if(count($last_db) > 0){
            $this->_print('Last database changelog : '.join('; ',$last_db));
        }

        $this->_print('==================');

        $missing_files = array();
        foreach($this->_missing_files as $module => $files){
            $missing_files[] = $module.' ['.join(', ',$files).']';
        }

        if(count($missing_files) > 0){
            $this->_print('Missing files: '.join(', ', $missing_files));
        }else{
            $this->_print('All files load: [OK]');
        }


        $missing_migration = array();
        foreach($this->_missing_migration as $module => $files){
            $missing_migration[] = $module.' ['.join(', ',$files).']';
        }

        if(count($missing_migration) > 0){
            $this->_print('Missing migration: '.join('; ', $missing_migration));
        }else{
            $this->_print('All migration done: [OK]');
        }
	}

    public function update($module = null, $numbers = null)
    {
        if(!empty($module) && !$this->_frontController->getModuleDirectory($module)){
            $this->_print('Module ('.$module.') NOT Exists');
            return;
        }

        $this->getMigrationDiff();

        $update_migration = array();
        if(!empty($module)){
            $num = array();
            if(!empty($numbers)){
                $num = array_map('intval',explode(',',$numbers));
                foreach($num as &$v){
                    $v = str_pad($v, 4, "0", STR_PAD_LEFT).'.sql';
                }
            }else{
                $num = $this->_files[$module];
            }

            $numbers = array_intersect($this->_files[$module], $num);
            $update_migration[$module] = $this->_arrayRecursiveDiff($numbers, (array)@$this->_database[$module]);
        }else{
            $numbers = $this->_missing_migration;

//            $numbers = array_intersect($this->_files, $numbers);
            $update_migration = $this->_arrayRecursiveDiff($numbers, $this->_database);
        }


        if(count($update_migration) === 0){
            $this->_print('Database is actual.');
            return;
        }

        $update_info = array('count' => 0, 'files' => array());
        foreach($update_migration as $mod => $files){
            $update_info['count']+= count($files);
            $update_info['files'][] = $mod.': ('.join(',', $files).')';
        }

        $this->_print('SQL files to migrate ['.$update_info['count'].']: '.join('; ', $update_info['files']));

        foreach($update_migration as $mod => $files){
            $conn = Doctrine_Manager::connection();
            $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
            foreach($files as $file){
                $filename = $file_path = $this->_frontController->getModuleDirectory($mod).DS.'data'.DS.'migration'.DS.$file;
                try{
                    $conn->beginTransaction();
                    $sql = file_get_contents($filename);

                    $dbh->query($sql);

                    $databaseChangelog = new DatabaseChangelog();
                    $databaseChangelog->id_database_changelog = $file;
                    $databaseChangelog->module = $mod;
                    $databaseChangelog->save();

                    $this->_print('Execute migration file: '.$mod.' - '.$file.' [OK]');
                    $conn->commit();
                }catch (Exception $e){
                    $conn->rollback();
                    $this->_print('Execute migration file: '.$mod.' - '.$file.' [ERROR]');
                    $this->_print('=========================================');
                    $this->_print($e);
                    return;
                }
            }
        }

    }

    public function show( $module, $number )
    {
        if(!$this->_frontController->getModuleDirectory($module)){
            $this->_print('Module ('.$module.') NOT Exists');
            return;
        }

        $this->loadFiles();

        $number = intval( $number );
        $number = str_pad($number, 4, "0", STR_PAD_LEFT).'.sql';
        $this->_print('Show SQL file: '.$number.', module: '.$module);
        $fileContent = 'Migration file not exists';

        if(isset($this->_files[$module][$number])){
            $file = $this->_files[$module][$number];
            $file_path = $this->_frontController->getModuleDirectory($module).DS.'data'.DS.'migration'.DS.$file;
            if ( file_exists($file_path)) {
                $fileContent = file_get_contents($file_path);
            }
        }

        echo "\r\n\r\n". $fileContent;
        echo PHP_EOL;
    }

    public function mark($module, $numbers)
    {
        if(!$this->_frontController->getModuleDirectory($module)){
            $this->_print('Module ('.$module.') NOT Exists');
            return;
        }

        $this->getMigrationDiff();
        $markSql = array_map( 'intval', explode( ',', $numbers ) );
        foreach ($markSql as &$number) {
            $number = str_pad($number, 4, "0", STR_PAD_LEFT).'.sql';
        }

        $update_migration = array();
        if(isset($this->_files[$module])){
            $markSql = array_intersect( $this->_files[$module], $markSql );
            if(isset($this->_database[$module])){
                $update_migration = array_diff_key($markSql, $this->_database[$module]);
            }else{
                $update_migration = $markSql;
            }

        }

        if ( count( $update_migration ) === 0 ) {
            $this->_print('Database Changelog is actual.');
            return;
        }

        $this->_print('Mark SQL in database_changelog ('.$module.'):' . join(',', $update_migration));

        $module_path =  $this->_frontController->getModuleDirectory($module).DS.'data'.DS.'migration';
        foreach ($update_migration as $file) {
            $filename = $module_path.DS.$file;
            if ( file_exists($filename) ) {
                try {
                    $databaseChangelog = new DatabaseChangelog();
                    $databaseChangelog->id_database_changelog = $file;
                    $databaseChangelog->module = $module;
                    $databaseChangelog->save();
                    $this->_print('Done');
                } catch ( Exception $e ) {
                    $this->_print('Execute migration file: '.$file.' [ERROR]');
                    $this->_print('=========================================');
                    $this->_print($e);
                    return;
                }
            } else {
                $this->_print('File not exist');
            }
        }


        exit();
    }


    private function getMigrationDiff()
    {
        $this->loadFiles();
        $this->loadDatabase();

        $this->_missing_migration = $this->_arrayRecursiveDiff($this->_files, $this->_database);
        $this->_missing_files = $this->_arrayRecursiveDiff($this->_database, $this->_files);
    }

    private function loadFiles()
    {
        $modulesEnabled = $this->_frontController->getControllerDirectory();

        foreach($modulesEnabled as $module => $controllerPath){
            $modelPath = $this->_frontController->getModuleDirectory($module).DS.'data'.DS.'migration';

            if(is_dir($modelPath)){
                foreach (scandir($modelPath) as $file) {
                    if ($file{0} == '.') continue;
                    $ext = Base::getFileExt($file);
                    if($ext != 'sql'){ continue; }
                    $this->_files[$module][$file] = $file;
                }
            }
        }
    }


    private function loadDatabase()
    {
        $result = Doctrine_Query::create()
            ->from('DatabaseChangelog')
            ->addWhere('type = 1')
            ->orderBy('module, executed_at ASC')
            ->fetchArray();

        $data = array();
        foreach($result as $v) {
            $data[$v['module']][$v['id_database_changelog']] = $v['id_database_changelog'];
        }

        $this->_database = $data;
    }


	private function _print($message, $show_timer = true)
	{
		if ($show_timer) {
			$time = $this->__microtime_float() - $this->timer;
			$this->_registry->getResponse()->appendContent(" [" . number_format($time, 2) . " sec] " . $message, array('color' => 'hiGreen'));
			$this->_resetTimer();
		} else {
			$this->_registry->getResponse()->appendContent($message, array('color' => 'hiGreen'));
		}
	}

	private function _resetTimer()
	{
		$this->timer = $this->__microtime_float();
	}

	private function __microtime_float()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}


    private function _arrayRecursiveDiff($aArray1, $aArray2) {
        $aReturn = array();

        foreach ($aArray1 as $mKey => $mValue) {
            if (array_key_exists($mKey, $aArray2)) {
                if (is_array($mValue)) {
                    $aRecursiveDiff = self::_arrayRecursiveDiff($mValue, $aArray2[$mKey]);
                    if (count($aRecursiveDiff)) { $aReturn[$mKey] = $aRecursiveDiff; }
                } else {
                    if ($mValue != $aArray2[$mKey]) {
                        $aReturn[$mKey] = $mValue;
                    }
                }
            } else {
                $aReturn[$mKey] = $mValue;
            }
        }

        return $aReturn;
    }

}

