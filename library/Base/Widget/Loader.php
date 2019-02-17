<?php

class Base_Widget_Loader
{
    /**
     * @var Base_Doctrine_Record|array
     */
    protected $_model = false;

    protected $_channel = null;

    protected $_id_col = null;

    public function __construct($options = array())
    {
        if(isset($options['model'])){
            $this->setModel($options['model']);
        }
    }


    public function getModel()
    {
        return $this->_model;
    }

    public function setModel($model)
    {
        $this->_model = $model;

        return $this;
    }


    public function getParamsByWidget($widget)
    {
        $params = array();

        if(!$this->_channel || !$this->_id_col ){
            return $params;
        }

        if($widget == 'Client_Widget_Card')
        {
            if(isset($this->_model['Client']) && is_array($this->_model['Client'])){
                $params = array('client' => $this->_model['Client']);
            }else{
                $params = array('client' => $this->_model[$this->_id_col]);
            }
        }

        elseif($widget == 'Document_Widget_Channel')
        {
            $params = array(
                'channel' => $this->_channel,
                'object_id' => $this->_model[$this->_id_col]
            );
        }

        elseif($widget == 'Note_Widget_Note')
        {
            $params = array(
                'channel' => $this->_channel,
                'object_id' => $this->_model[$this->_id_col]
            );
        }

        elseif($widget == 'Log_Widget_Timeline')
        {
            $params = array(
                'channel' => $this->_channel,
                'id_object' => $this->_model[$this->_id_col]
            );
        }

        elseif($widget == 'Person_Widget_Person')
        {
            $params = array(
                $this->_id_col => $this->_model[$this->_id_col]
            );
        }

        elseif($widget == 'Task_Widget_TaskTable')
        {
            $params = array(
                $this->_id_col => $this->_model[$this->_id_col]
            );
        }

        elseif($widget == 'Invoice_Widget_Table')
        {
            $params = array(
                $this->_id_col => $this->_model[$this->_id_col]
            );
        }

        elseif($widget == 'Bill_Widget_List')
        {
            $params = array(
                $this->_id_col => $this->_model[$this->_id_col],
                'useAddButton' => true,
            );
        }

        elseif($widget == 'Agreement_Widget_List')
        {
            $params = array(
                $this->_id_col => $this->_model[$this->_id_col],
                'useAddButton' => true,
            );
        }

        elseif($widget == 'Outlook_Widget_List')
        {
            $domain = null;

            if($this->_model['email']){
                $domain = str_replace('@', '', strstr($this->_model['email'], '@'));
            }

            if(!$domain && $this->_model['www']){
                $parse = parse_url($this->_model['www']);
                $domain = str_replace('www.', '',$parse['host']);
            }

            $params = array(
                'domain' => $domain,
            );
        }



        return $params;
    }

}
