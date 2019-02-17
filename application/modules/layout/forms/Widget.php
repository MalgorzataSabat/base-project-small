<?php

class Layout_Form_Widget extends Base_Form_Horizontal
{

//    protected $_belong_to = 'PageFormAdminPage';

//    protected $_tlabel = 'form_page_admin_widget_';

    public function init()
    {
        $this->setDecorators(array(
            'FormElements', new Layout_Form_Decorator_Widget()
        ));
    }

}