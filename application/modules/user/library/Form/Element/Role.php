<?php


class User_Form_Element_Role extends Base_Form_Element_Select
{
    private $_defaultName       = 'role';

    private $_options = array();

    public function __construct($name = null, $options = array())
    {
        empty($name) && $name = $this->_defaultName;

        $this->_options = $options;

        if ( !array_key_exists('label', $options) ) {
            $options['label'] = 'field_user_role';
        }

        $options['prefixPath'] = array(
            'decorator' => array('Base_Form_Decorator' => 'Base/Form/Decorator')
        );

        parent::__construct($name, $options);

        if ( !array_key_exists('multioptions', $this->_options) ) {
            $this->loadMultiOptions($this->_options);
        }

    }

    public function loadMultiOptions($options = array())
    {
        $multiOptions = array();
        $object = isset($options['object']) ? $options['object'] : null;
        $useIds = isset($options['useIds']) ? $options['useIds'] : false;
       // $keyOption = $useIds ? 'id_acl_role' : 'name_role';

        $keyOption = 'id_acl_role';

        $isRequired = $this->isRequired();
        if(!$isRequired){
            $multiOptions[] = '';
        }

        $aclRoleList = AclRole::getList(array(
            'coll_key' => 'name', 'is_visible' => '1',
            'object' => $object,
        ));


        $multiOptions[] = '--- wybierz ---';
        foreach($aclRoleList as $aclRole){

            $multiOptions[$aclRole[$keyOption]] = $aclRole['name'];
        }

       // if(!$object && !$useIds){
       //     $multiOptions['admin']= 'role_name_admin';
      //  }

        $this->setMultiOptions($multiOptions);
    }
}