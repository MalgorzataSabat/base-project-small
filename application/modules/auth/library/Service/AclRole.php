<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.03.2018
 * Time: 15:55
 */

class Auth_Service_AclRole
{
    /**
     * @var AclRole
     */
    protected static $_list;


    protected static function getList()
    {
        if(!self::$_list)
        {
            self::$_list = AclRole::getQuery(array(
                'coll_key' => 'id_acl_role',
                'id_service' => null
            ))->fetchArray();
        }
        return self::$_list;
    }

    public static function setAclRole($aclRoleModel)
    {
        $model = null;
        if(!self::hasRole())
        {
            $last_id = null;
            $list = self::getList();
            if($aclRoleModel instanceof AclRole)
            {
                $list[$aclRoleModel['id_acl_role']] = $aclRoleModel->toArray();
            } elseif(is_array($aclRoleModel)) {
                $list[$aclRoleModel['id_acl_role']] = $aclRoleModel;
            }

            foreach($list as $role)
            {

                $aclRole = new AclRole();
                $aclRole->id_parent = $role['id_parent'] == null ? null : $last_id[$role['id_parent']];
                $aclRole->order = $role['order'];
                $aclRole->is_visible = $role['is_visible'];
                $aclRole->name = $role['name'];
                $aclRole->save();

                $last_id[$role['id_acl_role']] = $aclRole['id_acl_role'];
                if($aclRoleModel['id_acl_role'] == $role['id_acl_role'])
                {
                    $model = $aclRole;
                }
            }
        }

        return $model;
    }

    public static function hasRole()
    {
        $role = Doctrine_Query::create()
            ->from('AclRole o')
            ->addWhere('o.id_service = ?', Base_Service::getId())
            ->fetchArray();

        return (bool) $role;
    }


}