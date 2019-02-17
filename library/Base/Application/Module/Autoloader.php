<?php
/**
 * Short info:
 * - change resource initialization (initDefaultResources)
 * - scripts looking for classes only in library and models
 */
class Base_Application_Module_Autoloader extends Zend_Loader_Autoloader_Resource
{
    /**
     * init default resource types
     */
    public function __construct($options)
    {
        parent::__construct($options);
        $this->initDefaultResourceTypes();
    }

    public function initDefaultResourceTypes()
    {
        $this->addResourceTypes(array(
            'form' => array(
                'path' => 'forms',
                'namespace' => 'Form',
            ),
            'widget' => array(
                'path' => 'widgets',
                'namespace' => 'Widget')
            )
        );
    }

    /**
     * Helper method to calculate the correct class path
     *
     * @param string $class
     * @return False if not matched other wise the correct path
     */
    public function getClassPath($class)
    {
        $match = parent::getClassPath($class);

        if(!$match){
            $segments = explode('_', $class);
            if(count($segments) > 1){
                array_shift($segments);
                $classPath = $this->getBasePath() . '/library/' . join('/', $segments) . '.php';
                if (Zend_Loader::isReadable($classPath)) {
                    return $classPath;
                }
            }
        }

        return $match;
    }
}
