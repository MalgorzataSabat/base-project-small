<?php


class Base_Widget
{
    private static $registred_widgets = array();

    /**
     * Method provide functionality of regitering widgets and widget configs
     * @static
     * @param $widget
     * @param string $type
     * @throws Exception
     */
    public static function registerWidget($widget, $type = "default")
    {
        if (isset(self::$registred_widgets[$widget])) {
            throw new Exception("Widget alias already taken, please choose another one.");
        }
//        $obj = new $widget;
        self::$registred_widgets[$type][$widget] = $widget;
    }

    /**
     * Method by which you can get the widget by name. Widget must be pre-registered.
     * @see Base_Widget::registerWidget
     *
     * @static
     * @param $name
     * @param string $type
     * @throws Exception
     * @return
     */
    public static function getWidget($name, $type = "default")
    {
        if (!isset(self::$registred_widgets[$type][$name])) {
            throw new Exception("Widget '$name' is not registred.\n");
        }
        return self::$registred_widgets[$type][$name];
    }

    public static function getWidgetList($type = "default")
    {
        if ($type === 'all') {
            return self::$registred_widgets;
        } else {
            return self::$registred_widgets[$type];
        }
    }

    public static function widgetTypeExist($type = "default")
    {
        if (isset(self::$registred_widgets[$type])) {
            return true;
        }

        return false;
    }

}