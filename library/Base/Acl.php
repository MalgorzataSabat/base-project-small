<?php
class Base_Acl extends Zend_Acl
{

	const DEFAULT_POLICY = 'allow';
   // const DEFAULT_POLICY = 'deny';

    /**
     * @var Base_Acl
     */
    private static $_acl;

    private $_my_role = null;

    private $_resourceItems = array();

    private $_resourceFullList = array();

    protected $_resourceAssertList = array();

    /**
     * @return Base_Acl
     */
  /*  public static function getInstance()
    {
        if ( is_null( self::$_acl ) ) {
            $cache = Base_Cache::getCache();
            $cache_name = Base_Service::isIdentity() ? 'Base_Acl_'.Base_Service::getId() : 'Base_Acl';
            self::$_acl = $cache->load($cache_name);

            if( !self::$_acl ){
                self::$_acl = new self();
                self::$_acl->loadRolesResourcesRules();
                $cache->save(self::$_acl, $cache_name);
            }

        }

        return self::$_acl;
    }
*/


}

