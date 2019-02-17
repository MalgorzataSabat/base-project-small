<?php


class Base_Layout_Provider extends Zend_Layout
{

    private $_placeholders = array();

    /**
     * Static method for initialization with MVC support
     *
     * @param  string|array|Zend_Config $options
     * @return Base_Layout_Provide
     */
    public static function startMvc($options = null)
    {
        if (null === self::$_mvcInstance) {
            self::$_mvcInstance = new self($options, true);
        }

        if (is_string($options)) {
            self::$_mvcInstance->setLayoutPath($options);
        } elseif (is_array($options) || $options instanceof Zend_Config) {
            self::$_mvcInstance->setOptions($options);
        }

        return self::$_mvcInstance;
    }


    /**
     * @param $script
     * @param $name
     * @return $this
     */
    public function addScriptToPlaceholder($script, $name)
    {
        $this->_placeholders[$name][] = $script;

        return $this;
    }



    /**
     * Render layout
     *
     * Sets internal script path as last path on script path stack, assigns
     * layout variables to view, determines layout name using inflector, and
     * renders layout view script.
     *
     * $name will be passed to the inflector as the key 'script'.
     *
     * @param  mixed $name
     * @return mixed
     */
    public function render($name = null)
    {
        if (null === $name) {
            $name = $this->getLayout();
        }

        if ($this->inflectorEnabled() && (null !== ($inflector = $this->getInflector())))
        {
            $name = $this->_inflector->filter(array('script' => $name));
        }

        $view = $this->getView();

        if (null !== ($path = $this->getViewScriptPath())) {
            if (method_exists($view, 'addScriptPath')) {
                $view->addScriptPath($path);
            } else {
                $view->setScriptPath($path);
            }
        } elseif (null !== ($path = $this->getViewBasePath())) {
            $view->addBasePath($path, $this->_viewBasePrefix);
        }


        foreach($this->_placeholders as $placeholder => $scripts){
            foreach($scripts as $script){
                $view->renderToPlaceholder($script, $placeholder);
            }
        }


        return $view->render($name);
    }
}
