<?php


class User_Template_Maker_Login extends User_Template_Maker implements Template_Maker_Interface
{

    protected $_prefixKey = 'userlogin';

    public function __construct($options = array())
    {
        $this->setData(Base_Auth::getUser());

        parent::__construct($options);
    }

}