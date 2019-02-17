<?php


class User_Form_Field extends  Field_Form_Field_Abstract
{

    public function createElement($field, $fieldOptions, $form)
    {
        return new User_Form_Element_User($field['hash'], $fieldOptions);
    }


}