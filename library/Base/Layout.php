<?php

class Base_Layout
{

    private static $_layouts = array();

    private static $_current_layout = null;

    private static $_basicScripts = null;


    /**
     * Może w przyszłości ładować tylko layouty systmowe?
     */
    public static function loadLayouts()
    {
        $cache = Base_Cache::getCache('default');
        $cache_name = self::getCacheName();
        self::$_layouts = $cache->load( $cache_name );

        if(self::$_layouts === false ){
            $layouts = Layout::getList(array('coll_key' => 'id_layout'));
            $layoutsTemplates = LayoutTemplate::getList(array('coll_key' => 'id_layout_template'));

            self::$_layouts['layouts'] = $layouts;
            foreach($layouts as $layout){
                self::$_layouts['layouts'][$layout['id_layout']]['data_map'] = (array) json_decode($layout['data_map'], true);

                if($layout['is_default'] && $layout['type']){
                    self::$_layouts['type'][$layout['type']] = $layout['id_layout'];
                }
            }

            foreach($layoutsTemplates as $lTemplate){
                self::$_layouts['templates'][$lTemplate['id_layout_template']] = $lTemplate;
            }

            $cache->save(self::$_layouts, $cache_name);
        }
    }


    public static function setLayoutByType($type)
    {
        if (!isset(self::$_layouts['type'][$type])) {
            throw new Exception('Type Layout '.$type.' is not registred.');
        }

        self::setLayout(self::$_layouts['type'][$type]);
    }

    /**
     * Method by which you can select the layout. Layout must be pre-registered.
     *
     * @static
     * @throws Exception
     */
    public static function setLayout($id_layout)
    {
        $view = self::getView();

        if (!isset(self::$_layouts['layouts'][$id_layout])) {
            throw new Exception('ID Layout '.$id_layout.' is not registred.');
        }
        self::$_current_layout = self::$_layouts['layouts'][$id_layout];

        if(null === self::$_basicScripts){
            self::$_basicScripts = $view->getScriptPaths();
        }else{
            $view->setScriptPath(array_reverse(self::$_basicScripts));
        }


        /** @var $layout Zend_Layout */
        $layout = $view->layout();
        $view->addBasePath(APPLICATION_PATH . '/modules/layout/layouts/');

        $layout->setLayout(self::$_layouts['templates'][self::$_current_layout['id_layout_template']]['filename']);
    }


    public static function getLayout($id_layout = null)
    {
        if(null === $id_layout){
            $id_layout = self::$_current_layout['id_layout'];
        }

        return self::$_layouts['layouts'][$id_layout];
    }

    public static function getLayoutTemplate($id_layout_template)
    {
        return self::$_layouts['templates'][$id_layout_template];
    }

    public static function getLayoutTemplates()
    {
        return self::$_layouts['templates'];
    }


    public static function disableLayout()
    {
        self::getView()->layout()->disableLayout();
    }

    public static function disableView()
    {
        Zend_Controller_Front::getInstance()->setParam('noViewRenderer', true);
    }



    /**
     * Get current Zend_View object
     *
     * @static
     * @return Zend_View
     */
    public static function getView()
    {
        /** @var $bootstrap Bootstrap */
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');

        /** @var $layout Zend_Layout */
        $layout = $bootstrap->getResource('layout');
        return $layout->getView();

    }



//    public static function getWidgetScriptPaths($module)
//    {
//        $result = array(
//            APPLICATION_PATH."/modules/".$module."/views/widgets"
//        );
//
//        $view = self::getView();
//        $scripts = $view->getScriptPaths();
//
////        $patterns = array();
////        $replacements = array();
////
////        $patterns[0] = '/(.*modules\/)(.*)((layouts|views).*)(scripts)(.*)/';
////        $replacements[0] = '$1'.$module.'/$3widgets$6';
////
////        $patterns[1] = '/(.*)(scripts)(.*)/';
////        $replacements[1] = '$1widgets$3';
////
////        foreach($scripts as $v){
////            $result[] = preg_replace($patterns, $replacements, $v);
////        }
////
////        $result[] = APPLICATION_PATH."/views/scripts/";
//
//        return $result;
//    }


    public static function cleanCache()
    {
        $cache = Base_Cache::getCache();
        $cache->remove(self::getCacheName());
    }

    public static function getCacheName()
    {
        return 'layouts';
    }


}