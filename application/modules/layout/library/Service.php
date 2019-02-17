<?php
class Layout_Service{

    private $_type = null;

    private $_layout = null;

    private $_layoutTemplate = null;

    /**
     * @var Base_Widget_Loader
     */
    private $_loader = null;

    /**
     * @var null|Zend_Session_Namespace
     */
    private $_session = null;

    public function __construct($type, $id_layout = null)
    {
        $this->setType($type);

        $request = Zend_Controller_Front::getInstance()->getRequest();
        !$id_layout && $id_layout = $request->getParam('id_layout', null);

        $this->_layoutList = Layout::getList(array(
            'coll_key' => 'id_layout', 'id_user_or_public' => Base_Auth::getUserId(),
            'type' => $this->_type
        ));

        $this->_session = new Zend_Session_Namespace($this->_type);

        if($id_layout && !isset($this->_layoutList[$id_layout])){
            $id_layout = null;
        }

        if(null === $id_layout){
            if($this->_session->__isset('id_layout')){
                $id_layout = $this->_session->__get('id_layout');
            }
        }

        if(!$id_layout){
            foreach($this->_layoutList as $id => $layout){
                if($layout['is_default'] && $layout['id_user'] == Base_Auth::getUserId()){
                    $id_layout = $id;
                    break;
                }
            }
        }

        if(!$id_layout || !isset($this->_layoutList[$id_layout])){
            $id_layout = array_values($this->_layoutList)[0]['id_layout'];
        }

        $this->_session->__set('id_layout', $id_layout);
        $this->_layout = $this->_layoutList[$id_layout];
        $this->_layoutTemplate = Base_Layout::getLayoutTemplate($this->_layout['id_layout_template']);
    }


    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param $type
     * @return $this
     */
    public function setType($type)
    {
        $this->_type = $type;

        return $this;
    }


    public function getLayoutList()
    {
        return $this->_layoutList;
    }


    public function getLayout($col = null)
    {
        if($col && isset($this->_layout[$col])){
            return $this->_layout[$col];
        }

        return $this->_layout;
    }

    public function getLayoutId()
    {
        return $this->getLayout('id_layout');
    }


    public function setLoader($loader, $options = array())
    {
        if(is_string($loader)){
            $loader = new $loader($options);
        }

        $this->_loader = $loader;

        return $this;
    }




    public function render()
    {
        $data_map = json_decode($this->_layout['data_map'], true);
        $view = Base_Layout::getView();

        foreach($data_map as $name => $values){
            if(isset($values['widgets']) && count($values['widgets']) > 0)
            {
                foreach($values['widgets'] as $widget){
                    if(class_exists($widget['_class'])){
                        $widgetClass = new $widget['_class'];

                        if($this->_loader){
                            $widget+= $this->_loader->getParamsByWidget($widget['_class']);
                        }

                        unset($widget['_class']);

                        $view->placeholder($name)->append($widgetClass->render($widget));
                    }
                }
            }
        }

        return $view->render($this->_layoutTemplate['filename'].'.phtml');
    }

}