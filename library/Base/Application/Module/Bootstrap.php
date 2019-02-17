<?php

class Base_Application_Module_Bootstrap extends Zend_Application_Module_Bootstrap
{

    public function getResourceLoader()
    {
        if ((null === $this->_resourceLoader) && (false !== ($namespace = $this->getAppNamespace()))) {
            $r = new ReflectionClass($this);
            $path = $r->getFileName();
            $this->setResourceLoader(new Base_Application_Module_Autoloader(array(
                'namespace' => $namespace,
                'basePath' => dirname($path),
            )));
        }
        return $this->_resourceLoader;
    }

}