<?php

class User_Form_Filter extends Base_Form_Filter {

    protected $_sortCols        = array(
        'id_user' => 'o.id_user',
        'fullname' => 'fullname',
        'name' => 'o.name',
        'surname' => 'o.surname',
        'email' => 'email',
        'created_at' => 'o.created_at',
        'archived_at' => 'o.archived_at',
        'role' => 'o.role',
        'description' => 'o.description',

    );

    protected $_avalibleCols    = array(
        'id_user' => 'filter_user-column_id_user',
        'fullname' => 'filter_user-column_fullname',
        'name' => 'filter_user-column_name',
        'surname' => 'filter_user-column_surname',
        'email' => 'filter_user-column_email',
        'created_at' => 'filter_user-column_created_at',
        'archived_at' => 'filter_user-column_archived_at',
        'role' => 'filter_user-column_role',
        'description' => 'filter_user-column_description',
    );


    public function init()
    {

   }
}