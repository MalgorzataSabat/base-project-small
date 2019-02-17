<?php
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 2015-03-05
 * Time: 16:47
 */
class Layout_Bootstrap extends Base_Application_Module_Bootstrap
{
    public function _initLayouts()
    {
        Base_Widget::registerWidget('Layout_Widget_Welcome', 'dashboard');
    }
}