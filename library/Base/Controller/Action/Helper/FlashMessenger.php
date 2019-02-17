<?php


class Base_Controller_Action_Helper_FlashMessenger extends Zend_Controller_Action_Helper_FlashMessenger
{
    protected $_tag = 'div';

    public function __construct( $config = array() )
    {
        parent::__construct();

        if(isset($config['tag'])) {
            $this->setTag($config['tag']);
        }
        
        if(isset($config['tagList'])) {
            $this->setTagList($config['tagList']);
        }
  
        if(isset($config['tagListItem'])) {
            $this->setTagListItem($config['tagListItem']);
        }
  
    }
    
    
    public function __get($namespace)
    {
        $this->setNamespace($namespace);
        return $this;
    }


    public function getMessages($translate = true)
    {
        $_xhtml = '';
        if($translate){
            $view = new Zend_View();
        }

        foreach(parent::$_messages as $_namespace => $_messages){
            $this->setNamespace($_namespace);
            if($this->hasMessages()) {
                $messagesList = parent::getMessages();
                foreach($messagesList as $message){
                    $message = $translate ? $view->translate($message) : $message;
                    $_xhtml.= '<div class="alert alert-'.$_namespace.'">' .$message.'</div>';
                }
            }

        }

        return $_xhtml;
    }
    
    
    /**
     * @param string $tag
     * @return Base_Controller_Action_Helper_FlashMessenger
     */
    public function setTag($tag)
    {
        $this->_tag = $tag;
        
        return $this;
    }
    
   
    /**
     * @param string $tag
     * @return Base_Controller_Action_Helper_FlashMessenger
     */
    public function setTagList($tagList)
    {
        $this->_tagList = $tagList;
        
        return $this;
    }
    
    /**
     * @param string $tag
     * @return Base_Controller_Action_Helper_FlashMessenger
     */
    public function setTagListItem($tagListItem)
    {
        $this->_tagListItem = $tagListItem;
        
        return $this;
    }
}
