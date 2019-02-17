<?php
class Base_Auth extends Zend_Auth
{
    
    const SALT = 'MjNkMTI2MmUwODBkZTAwMzQ3N2EwYjQzOWRhODI5MTA4Z';

    const MAIN_ROLE = 'guest';
    const MAIN_ROLE_LOGIN = 'user';
    const MAIN_ROLE_ADMIN = 'admin';
    const MAIN_ROLE_SUPERADMIN = 'superadmin';

	/**
	 * @static
	 * @param null $column
	 * @return mixed
	 */
    public static function getUser($column = null)
    {

        $auth = Zend_Auth::getInstance();

        if ($auth->hasIdentity()) {
            if (!empty($column)) {
                $tmp = Zend_Auth::getInstance()->getStorage()->read();
                if(isset($tmp->{$column}))return $tmp->{$column};
            } else {
                return Zend_Auth::getInstance()->getStorage()->read();
            }
        }

        return null;
    }


    /**
     * @return int
     */
    public static function getUserId()
    {
        return self::getUser('id_user');
    }


    public static function getUserRoles($roles = null)
    {
        if($roles == null){
            $roles = self::getUser('role');
        }

        $user_roles = array(self::MAIN_ROLE);

        if(Base_Auth::isIdentity()){
            $user_roles[] = self::MAIN_ROLE_LOGIN;
        }

        $user_roles = array_unique(array_filter(array_merge(explode(',', $roles))));

        return $user_roles;
    }

    /**
     * @return boolean
     */
    public static function isIdentity()
    {
        return Zend_Auth::getInstance()->hasIdentity();
    }
    
    
	/**
	 * Returns hash for plain text password
	 *
	 * @static
	 * @param $pass
	 * @return string
	 */
	public static function hashPassword($pass)
	{
		return hash('md5', md5($pass) . self::SALT);
	}

    /**
     * @param $user
     * @throws Exception
     */
    public static function loginUser($user)
    {
        if($user instanceof User) {
            $user = $user->toArray(0);
        }

        $objectStd = new stdClass();
        foreach ( (array) $user as $column => $value ) {
            $objectStd->{$column} = $value;
        }

        $auth = Zend_Auth::getInstance();
        $auth->getStorage()->write($objectStd);

        return true;
    }


    public static function reloadLoginUserData($user = null)
    {
        if(null === $user && self::isIdentity()){
            $user = User::findRecord(Base_Auth::getUserId());
        }

        if(null === $user){
            return false;
        }

        $user = $user->toArray(0);

        $objectStd = new stdClass();
        foreach ( (array) $user as $column => $value ) {
            $objectStd->{$column} = $value;
        }

        $auth = Zend_Auth::getInstance();
        $auth->getStorage()->write($objectStd);

        return true;
    }
    
}
