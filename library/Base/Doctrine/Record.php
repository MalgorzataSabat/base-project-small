<?php


abstract class Base_Doctrine_Record extends Doctrine_Record
{
    /**
     * @var integer
     */
    public $_typeLog;

    /**
     * @var array
     */
    public $_logData = array();

    protected static $_filedViewList = array();

     /**
     * @return int|null
     */
    public function getId()
    {
        $identifier = $this->getTable()->getIdentifier();
        return $this->$identifier;
    }


    /**
     * @return bool
     */
    public function isNew()
    {
        $id = $this->getId();
        return null === $id ? true : false;
    }


    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->is_active;
    }


    /**
     * @param array $options
     * @return Base_Query
     */
    public static function getQuery($options = array())
    {
        $tableName = isset($options['_tableName']) ? $options['_tableName'] : get_called_class();
        $table = Doctrine::getTable($tableName); /** @var $table Doctrine_Table **/
        $hydrate = isset($options['hydrate']) ? $options['hydrate'] : Doctrine::HYDRATE_ARRAY;
        $identifier = (array) $table->getIdentifier();

        $optionsSelect = $table->getOption('select');
        $select = 'o.*';

        if(isset($options['select'])) {
            $select = $options['select'];
        }elseif($hydrate == Doctrine::HYDRATE_ARRAY){
            if(!empty($optionsSelect)){
                $select = $optionsSelect;
            }
        }


        $coll_key = $table->getOption('coll_key');
        $coll_key = isset($options['coll_key']) ? 'INDEXBY o.'.$options['coll_key'] : (!empty($coll_key) ? 'INDEXBY o.'.$coll_key : '');

        $query = Doctrine_Query::create()
            ->select($select)
            ->from($tableName . ' o ' . $coll_key);


        $query->setHydrationMode($hydrate);

        if(isset($options['addSelect'])){
            foreach((array)$options['addSelect'] as $addSelect){
                $query->addSelect($addSelect);
            }
        }

        if($table->hasField('id_service') && $tableName != 'Service' && Base_Service::isIdentity()) {
            if(array_key_exists('id_service', $options) && null === $options['id_service']){
                $query->addWhere('o.id_service = ? OR o.id_service IS NULL', Base_Service::getId());
            }else{
                $query->addWhere('o.id_service = ?', Base_Service::getId());
            }
        }

        if ( $table->hasField('is_active', false) ) {
            if ( !array_key_exists('is_active', $options) ) {
                $query->addWhere('o.is_active = 1');
            } elseif ( strlen( $options['is_active'] ) > 0 ) {
                $query->addWhere('o.is_active = ?', $options['is_active']);
            }
        }

        if ( $table->hasField('archived_at', false) ) {
            if ( !array_key_exists('archived_at', $options) || !strlen( $options['archived_at'] )) {
                $query->addWhere('o.archived_at IS NULL');
            } elseif ( strlen( $options['archived_at'] ) > 1 ) {
                $query->addWhere('(o.archived_at IS NULL OR o.archived_at >= ?)', $options['archived_at']);
            }elseif ($options['archived_at'] === '1') {
                $query->addWhere('o.archived_at IS NOT NULL');
            }
        }

        if ( isset( $options['limit'] ) ) {
            $query->limit($options['limit']);
        }


        if(isset($options['id'])){
            if ( is_array( $options['id'] ) ) {
                $query->whereIn("o.$identifier[0]", $options['id']);
            } else {
                $query->addWhere("o.$identifier[0] = ?", $options['id']);
            }
        }

        if(isset($options['hash'])){
            $query->addWhere("o.hash = ?", $options['hash']);
        }

        if(isset($options['search']) && strlen($options['search']) && ($search = $table->getOption('search')) != ''){
            $query->addWhere("$search LIKE ?", '%' . $options['search'] . '%');
        }

        if(isset($options['order']) && ($options['order'])  != ''){
            $query->orderBy($options['order']);
        }

        if(isset($options['debug'])){
            echo '<pre>' . $query->getSqlQuery() . '</pre>';
            exit();
        }

        return $query;
    }


    /**
     * @param array $options
     * @return bool|Doctrine_Record|mixed
     * @throws Doctrine_Query_Exception
     */
    public static function getRecord($options = array())
    {
        $options['limit'] = 1;
        !isset($options['archived_at']) && $options['archived_at'] = '';

        if(!isset($options['_class'])){ $options['_class'] = get_called_class(); }

        $query = $options['_class']::getQuery($options);
        $result = $query->execute();

        if ( count( $result ) ) {
            if ( is_array( $result ) ) {
                return array_shift($result);
            } else {
                return $result->getFirst();
            }
        }

        return false;
    }


    /**
     * @param $id
     * @param array $options
     * @return bool|Doctrine_Record|mixed
     * @throws Exception
     */
    public static function find($id, $options = array())
    {
        if(empty($id)){
            throw new Exception('ID value is required in find method');
        }

        $options['is_active'] = '';
        $options['archived_at'] = '0'; // wszystkie
        if(!isset($options['_class'])){ $options['_class'] = get_called_class(); }

        $keyId = (strlen($id) == 32 && $options['_class'] != 'Session') ? 'hash' : 'id';
        $options[$keyId] = $id;

        return $options['_class']::getRecord($options);
    }


    /**
     * @param $id
     * @param array $options
     * @return bool|Doctrine_Record
     * @throws Exception
     */
    public static function findRecord($id, $options = array())
    {
        if(empty($id)){
            throw new Exception('ID value is required in find method');
        }

        $options['hydrate'] = Doctrine::HYDRATE_RECORD;
        if(!isset($options['_class'])){ $options['_class'] = get_called_class(); }

        return $options['_class']::find($id, $options);
    }


    public static function getById($id)
    {
        $_class = get_called_class();
        $list = $_class::loadList();

        return isset($list[$id]) ? $list[$id] : false;

    }

    /**
     * @param array $options
     * @return Doctrine_Collection|array
     * @throws Doctrine_Query_Exception
     */
    public static function getList($options = array())
    {
        if(!isset($options['_class'])){ $options['_class'] = get_called_class(); }

        return $options['_class']::getQuery($options)->execute();
    }


    public static function getNameById($id)
    {
        $_class = get_called_class();

        return $_class::getValueByCol($id, 'name');
    }


    public static function getValueByCol($id, $col)
    {
        $_class = get_called_class();
        $list = $_class::loadList();

        return isset($list[$id][$col]) ? $list[$id][$col] : null;
    }

    /**
     * @return array|null
     */
    public static function loadList()
    {
        $_class = get_called_class();
        if(null === $_class::$_loadList){
            $cache_name = strtolower($_class).'_load_list_'.Base_Service::getId();
            $cache = Base_Cache::getCache('default');
            $_class::$_loadList = $cache->load($cache_name);

            if($_class::$_loadList === false){
                $table = Doctrine::getTable($_class); /** @var $table Doctrine_Table **/
                $identifier = (array) $table->getIdentifier();
                $_class::$_loadList = $_class::getList(array('coll_key' => $identifier[0], 'archived_at' => '0', 'checkAccess' => false));
                $cache->save($_class::$_loadList, $cache_name);
            }
        }


        return $_class::$_loadList;
    }


    public static function getFieldView($column)
    {
        $_class = get_called_class();

        return isset($_class::$_filedViewList[$column]) ? $_class::$_filedViewList[$column] : false;
    }



    /**
     * @param string $method
     * @param array $args
     * @return mixed
     * @throws Doctrine_Record_UnknownPropertyException
     */
    public function __call($method, $args)
    {
        $lcMethod = substr($method, 0, 3);
        $field = Base::camelToLine(substr($method, 3));
        array_unshift($args, $field);

        if($this->getTable()->hasField($field)){
            $lcMethod = '_'.$lcMethod.'Field';
            return call_user_func_array( array($this, $lcMethod), $args);
        }else{
            return parent::__call($method, $args);
        }

    }


    /**
     * @param $method
     * @param $args
     * @return mixed
     * @throws Doctrine_Record_UnknownPropertyException
     */
    public static function __callStatic($method, $args)
    {
        $_class = get_called_class();
        $table = Doctrine::getTable($_class);
        $field = Base::camelToLine(substr($method, 3, -4));

        if($table->hasField($field)){
            $args[] = $field;
            return forward_static_call_array(array($_class, 'getValueByCol'), $args);
        }else{
            throw new Doctrine_Record_UnknownPropertyException(sprintf('Unknown method %s::%s', get_class(get_called_class()), $method));
        }
    }


    /**
     * @param $field
     * @return mixed
     * @throws Exception
     */
    protected function _getField($field)
    {
        if(isset($this->$field)){
            return $this->$field;
        }else{
            throw new Exception(sprintf('Unknown field (column) %s::%s', get_class($this), $field));
        }
    }


    /**
     * @param $field
     * @param $value
     * @param $id_language
     * @param array $values
     * @return $this
     * @throws Exception
     */
    protected function _setField($field, $value)
    {
        if(isset($this->$field)){
            $this->$field = $value;
        }else{
            throw new Exception(sprintf('Unknown field (column) %s::%s', get_class($this), $field));
        }

        return $this;
    }


    /**
     * @param $values
     */
    public function setFields($values)
    {
        $table = $this->getTable();
        foreach($values as $k => $v){
            if($table->hasField($k)){
                $method = 'set'.Base::lineToCamel($k);
                $this->$method($v, null, $values);
            }
        }
    }


    /**
     * @param $value
     * @return $this
     */
    public function setArchivedAt($value)
    {
        if((bool) $value && $this->archived_at === null){
            $this->archived_at = date("Y-m-d H:i:s");
        }

        if((bool) !$value && $this->archived_at !== null){
            $this->archived_at = NULL;
        }

        return $this;
    }

    public function setTags($value)
    {
        if($value){
            $tagService = new Tag_Service();
            $tagService->createTags($value, get_class($this));

            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        } else {
            $value = '[]';
        }

        return parent::setTags($value);
    }


    /**
     * @param $event
     */
    public function preDelete($event)
    {
        //$this->_typeLog = Log::TYPE_DELETE;
        //$this->preLogData();
        parent::preDelete($event);
    }


    /**
     * @param $event
     */
    public function preSave($event)
    {
        $table = $this->getTable();
        if ( $table->hasColumn('id_user_created') && !$this->id_user_created ) {
            $this->id_user_created = Base_Auth::getUserId();
        }

        if($table->getOption('name') != Service::class)
        {
            if ( $table->hasColumn('id_service') && !$this->id_service ) {
                $this->id_service = Base_Service::getId();
            }
        }

        if ( $table->hasColumn('hash') && !$this->hash ) {
            $this->hash = Base::getHash();
        }


      //  $this->preLogData();
        parent::preSave($event);
    }


    /**
     * @param $event
     */
    public function postSave($event)
    {
        parent::postSave($event);
       // $this->postLogData();
      //  $this->createNotify();

        $_class = get_called_class();
        if(property_exists($_class, '_loadList')){
            $cache_name = strtolower($_class).'_load_list_'.Base_Service::getId();
            $cache = Base_Cache::getCache('default');
            $cache->remove($cache_name);
        }
    }


    /**
     * @param $event
     */
    public function postDelete($event)
    {
        parent::postDelete($event);
        $this->postLogData();
        $this->createNotify();

        $_class = get_called_class();
        if(property_exists($_class, '_loadList')){
            $cache_name = strtolower($_class).'_load_list_'.Base_Service::getId();
            $cache = Base_Cache::getCache('default');
            $cache->remove($cache_name);
        }
    }


    /**
     * Preparation of data to log
     */
    protected function preLogData()
    {
        $this->_logData = array();
        if( $this->option('log') && empty($this->_logData))
        {
            $this->_logData = $this->getLogData();
        }
    }


    /**
     * Add logs to the database
     * @throws Exception
     */
    protected function postLogData()
    {
        if(!empty($this->_logData)){
            // save log
            $options = array();
            $options['model'] = get_class($this);
            $options['id_object'] = $this->getId();

            Log::addLog($this->_typeLog, $this->_logData, $options);
        }
    }

    /**
     * Add notify to the database
     * @throws Exception
     */
    protected function createNotify()
    {
        if(!$this->_logData) return;

        $notifyClassName = get_class($this).'_Notify';
        if(class_exists($notifyClassName) && method_exists($notifyClassName, 'createNotifyFromRecord')){
            $notifyClass = new $notifyClassName();
            $notifyClass->createNotifyFromRecord($this);
        }
    }


    /**
     * @return array
     * @throws Doctrine_Record_State_Exception
     */
    protected function getLogData()
    {
        $result = array();
        $this->option('log', false);

        if(empty($this->_typeLog)){
            if($this->state() == Doctrine_Record::STATE_TDIRTY) {
                $this->_typeLog = Log::TYPE_CREATE;
            }elseif($this->state() == Doctrine_Record::STATE_TCLEAN) {
                $this->_typeLog = Log::TYPE_DELETE;
            }else {
                $this->_typeLog = Log::TYPE_EDIT;
            }
        }

        if ($this->_typeLog == Log::TYPE_EDIT) {
            $preMod = (array) $this->getModified(true, false);
            $postMod = (array) $this->getModified(false, false);

            foreach($preMod as $k => $v){
                if($postMod[$k] instanceof Doctrine_Null) continue;

                $result[$k] = array($v, $postMod[$k]);
            }
        }else{
            if($this->_typeLog == Log::TYPE_CREATE) {
                $_v = array_filter($this->toArray(0));
            }elseif($this->_typeLog == Log::TYPE_DELETE){
                $_v = $this->toArray(0);
            }
            foreach($_v as $k => $v){
                $result[$k] = array($v);
            }
        }

        $result = $this->getLogDataRelations($result);
        $result = $this->getLogDataValues($result);
        $result = $this->getLogDataName($result);


        return $result;
    }

    /**
     * Change column values to specific name in model
     *
     * @param $result
     * @return mixed
     */
    protected function getLogDataValues($result)
    {
        return $result;
    }

    /**
     * Change column key to human title name
     *
     * @param $data
     * @return array
     */
    protected function getLogDataName($data)
    {
        $result = array();
        foreach($data as $k => $v){
            $column = $this->getTable()->getColumnDefinition($k);
            $log = isset($column['log']) ? $column['log'] : true;

            if($log){
                if($column && isset($column['title'])){
                    $key = $column['title'];
                }elseif($k != '_relations'){
                    $tableName = mb_strtolower($this->getTable()->getTableName());
                    $key = Base::getFiledName($tableName.'.'.$k);
                }else{
                    $key = $k;
                }

                $result[$key] = $v;
            }
        }

        return $result;
    }

    /**
     * Add ralation log
     * Example: return array: $data['_relation'] = array('id_object' => '', 'model' => '');
     *
     * @return array
     */
    protected function getLogDataRelations($data)
    {
        return $data;
    }


}