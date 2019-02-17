<?php

class Base_View_Helper_ImageList extends Zend_View_Helper_Abstract
{

    private $_renderView = null;

    /**
     * @var Doctrine_Collection
     */
    private $_imageList;

    private $_options = array(
        'router' => array(
            'edit' => array('route' => 'image_edit'),
            'delete' => array('route' => 'image_delete'),
        ),
    );

    public function imageList($channel, $object_id = null, $options = array())
    {
        $this->_options = array_replace_recursive($this->_options, $options);
        $view = clone $this->view;

        $query = Doctrine_Query::create()
            ->from('Image o')
            ->where('o.channel = ?', $channel);

        if(null === $object_id){
            $query->addWhere('o.object_id IS NULL');
        }elseif(!empty($object_id)){
            $query->addWhere('o.object_id = ?', $object_id);
        }


        $this->_imageList = $query->execute(array(), Doctrine::HYDRATE_ARRAY);

        if(null == $this->_renderView){
            $this->_renderView = 'image/_list.phtml';

//            $path = APPLICATION_PATH.DS.'modules'.DS.'image'.DS.'views';
//            $view->addBasePath($path);
        }


        $view->imageList = $this->_imageList;
        $view->options = $this->_options;

        return $view->render($this->_renderView);
    }
}