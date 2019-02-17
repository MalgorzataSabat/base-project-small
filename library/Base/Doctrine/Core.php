<?php

require_once 'Doctrine/Core.php';

class Base_Doctrine_Core extends Doctrine_Core
{

    /**
     * Generate a yaml schema file from an existing directory of models
     *
     * @param string $yamlPath Path to your yaml schema files
     * @param string $directory Directory to generate your models in
     * @param array  $options Array of options to pass to the schema importer
     * @return void
     */
    public static function generateModelsFromYaml($yamlPath, $directory, $options = array())
    {
        $import = new Base_Doctrine_Import_Schema();
        $import->setOptions($options);

        return $import->importSchema($yamlPath, 'yml', $directory);
    }

}