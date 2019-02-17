<?php

class Base_Session_SaveHandler_Doctrine implements Zend_Session_SaveHandler_Interface
{

    private $_sessionName;
    private $_session;
    private $_lifetime;

    public function __construct($config = array())
    {
        if(isset($config['lifetime'])){
            $this->setLifetime($config['lifetime']);
        }else{
            $this->setLifetime(ini_get('session.gc_maxlifetime'));
        }

    }


    /**
     * Destructor
     *
     * @return void
     */
    public function __destruct()
    {
        Zend_Session::writeClose();
    }

    public function setLifetime($lifetime)
    {
        $this->_lifetime = (int)$lifetime;
    }

    public function getLifetime()
    {
        return $this->_lifetime;
    }

    public function read($id)
    {
        $this->_session = Session::findRecord($id);
        if(empty($this->_session)) {
            $this->_session = new Session();
            $this->_session->id = $id;
            $this->_session->lifetime = $this->_lifetime;
            $this->_session->ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
            $this->_session->agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

//            return '';
        }

        return (string) $this->_session->data;
    }

    public function write($id, $data)
    {
    	$this->_session->address = $_SERVER['REQUEST_URI'];
        if(Base_Auth::isIdentity()){
            $this->_session->id_user = Base_Auth::getUserId();
        }

    	$this->_session->data = $data;
        $this->_session->modified = time();
        $this->_session->save();

        return true;
    }

    public function destroy($id)
    {
        if($this->_session->id == $id) {
            $this->_session->delete();
            return true;
        }
        return false;
    }

    public function gc($maxlifetime)
    {
        $gcSession = Doctrine_Query::create()
            ->from('Session s')
            ->where('s.modified < (? - s.lifetime)', time())
            ->execute();

        $gcSession->delete();
        return true;
    }

    public function open($save_path, $name)
    {
        $this->_sessionName = $name;
        return true;
    }

    public function close()
    {
        return true;
    }

}