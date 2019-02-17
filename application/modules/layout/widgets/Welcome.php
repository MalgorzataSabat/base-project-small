<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.12.2017
 * Time: 10:21
 */

class Layout_Widget_Welcome extends Base_Widget_Abstract
{
    public $name = 'label_layout_widget_welcome';

    public function getViewScriptsDir()
    {
        return dirname(__DIR__);
    }

    public function getRenderView()
    {
        return 'welcome.phtml';
    }

}
