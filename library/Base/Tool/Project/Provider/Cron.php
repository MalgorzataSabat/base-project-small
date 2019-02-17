<?php

class Base_Tool_Project_Provider_Cron extends Base_Tool_Project_Provider_Abstract {

    public function __construct()
    {
        $this->_resetTimer();
        $this->app = Zend_Registry::get('application');
        $bootstrap = $this->app->getBootstrap();
        Zend_Controller_Front::getInstance()->setParam('bootstrap', $bootstrap);
        $this->app->getBootstrap()->bootstrap('autoload');
        $this->app->getBootstrap()->bootstrap('database');
        $this->app->getBootstrap()->bootstrap('layout');
        $this->app->getBootstrap()->bootstrap('view');
    }

    /**
     * This method run a Cron and execute models in database from
     * table Cron. We can run all services from db or one chosen.
     *
     * @param null $service
     */
    public function run($service = null, $lang = 'pl')
    {
        Base_I18n::setRequest(new Zend_Controller_Request_Http());
        Base_I18n::setContentLanguage($lang);
        $serviceToRun = array();

        $this->_print("\f Cron run:", false);
        $this->_print('Provider init');

        if($service){
            $cronList = Cron::getList(array('coll_key' => 'service'));
            if(isset($cronList[$service])){
                $serviceToRun[] = $cronList[$service];
            }
        }else{
            $serviceToRun = Cron::getListToRun(array('coll_key' => 'service'));
        }

        foreach ($serviceToRun as $service) {
            try {
                if ( class_exists($service['service']) ) {
                    $model = new $service['service'];
                    $model->cron();
                    Cron::updateExecutionTime($service['service']);
                    $this->_print('Running service: '.$service['service'].' - [OK]');
                } else {
                    $this->_print('Service '.$service['service'].' not exist - [ERROR]');
                }
            } catch (Exception $e) {
                $this->_print('Running service: '.$service['service'].' - [ERROR]');
                var_dump($e->getMessage());
            }
        }

        $this->_print('====================================');
        $this->_print('All existing services run: [OK]');
    }

    /**
     * This method show available service which can be used by cron
     * Also we get information about activity of service
     */
    public function show()
    {
        $options['is_active'] = '';
        $this->_print("\f Show Cron Services:", false);

        $cronList = Cron::getList(array('coll_key' => 'service'));
        foreach ($cronList as $cron) {
            $this->_print($cron['service'].' - is active: '. (int)$cron['is_active'].'; Last run: '.$cron['last_execution_at'].'; Freq: '.$cron['frequency']);
        }
        $this->_print('=================================');
    }
}