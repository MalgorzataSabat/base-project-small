<?php


class Base_Mail extends Zend_Mail
{
    /**
     * @var array
     */
    private $_options;

    /**
     * @var Zend_Mail_Transport_Smtp
     */
    private $_transporter = null;

    /**
     * @var EmailSend
     */
    private $_emailSend;


    public function  __construct($options = array(), $transporter = null)
    {
        parent::__construct( 'utf-8' );

        $options = $options + Setting::getEmailSetting();
        $this->setOptions( $options );

        if(null !== $transporter){
            $this->setTransporter($transporter);
        }else{
            if( $this->_options['smtp_on'] ){
                $config = array(
                    'auth' => 'login',
                    'username' => $this->_options['username'],
                    'password' => $this->_options['password'],
                );


                $extra_keys = array('ssl','port');
                foreach($extra_keys as $key)
                {
                    if(isset($options[$key]) && !empty($options[$key])){
                        $config[$key] = $options[$key];
                    }
                }
                $this->setTransporter(new Zend_Mail_Transport_Smtp( $this->_options['transporter'], $config));
            }
            $this->setFrom( $this->_options['email_from'], $this->_options['name_from'] );
        }
    }

    /**
     * Method return Mail Transporter
     *
     * @return Zend_Mail_Transport_Smtp
     */
    public function getTransporter()
    {
        return $this->_transporter;
    }

    /**
     * Method set Mail Transporter
     *
     * @param Zend_Mail_Transport_Smtp $transporter
     * @return $this
     */
    public function setTransporter(Zend_Mail_Transport_Smtp $transporter)
    {
        $this->_transporter = $transporter;

        return $this;
    }


    public function setOptions( $options )
    {
        $this->_options = $options;
    }

    public function setBodyContent($content, $layout = '_mail_layout')
    {
        $view = Base_Layout::getView();
        $view->mail_content = $content;
        $this->setBodyHtml( $view->render($layout.'.phtml'),'utf-8');
        $this->setBodyText( strip_tags( str_replace(array('<br/>','<br>'), "\r\n", $view->mail_content ) ) ,'utf-8');

//        echo $view->render('_mail_layout.phtml');
//        exit();
    }

    public function setBodyTemplate($template)
    {
        $view = Base_Layout::getView();
        $view->mail_content = $view->render($template);
        $this->setBodyHtml( $view->render('_mail_layout.phtml'),'utf-8');
        $this->setBodyText( strip_tags( str_replace(array('<br/>','<br>'), "\r\n", $view->mail_content ) ) ,'utf-8');

//        echo $view->render('_mail_layout.phtml');
//        exit();
    }


    /**
     * @return EmailSend
     */
    public function getEmailSend()
    {
        return $this->_emailSend;
    }


    public function send( $send = null, $option = array())
    {
        $ret = false;
        if( null == $send ) {
            $send = true;
        }
        $this->_emailSend = isset($option['model']) ? $option['model'] : null;
        //$db_save = isset($option['db_save']) ? $option['db_save'] : true;


        if( $send ) {
            if( $this->_options['send_on'] ){
                try{
                    $ret = parent::send( $this->_transporter );

                }catch (Exception $e){
                    if(DEBUG){
                        throw new Exception($e);
                    }else{

                    }
                }
            }else{

            }
        }

        return $ret;
    }

    public function getMessageContent()
    {
        return $this->_transporter->header . Zend_Mime::LINEEND . $this->_transporter->body;
    }


}
