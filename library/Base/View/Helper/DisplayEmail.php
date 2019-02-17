<?php

class Base_View_Helper_DisplayEmail extends Zend_View_Helper_Abstract {
    
    public function displayEmail($email)
    {
        $email_part = explode('@', $email);

        $html = '<script type="text/javascript">';
            $html.= 'var first = "'.$email_part[0].'";';
            $html.= 'var at = "@";';
            $html.= 'var second = "'.$email_part[1].'";';
            $html.= 'var mail = "mail";';
            $html.= 'var to = "to";';
            $html.= 'document.write(\'<a class="mailTo" href="\' + mail + to + \':\' + first + at + second + \'">\' + first + at + second + \'</a>\')';
        $html.= '</script>';

        return $html;
    }
}