<?php

class Base_Doctrine_Import_Schema extends Doctrine_Import_Schema
{

    /**
     * importSchema
     *
     * A method to import a Schema and translate it into a Doctrine_Record object
     *
     * @param  string $schema       The file containing the XML schema
     * @param  string $format       Format of the schema file
     * @param  string $directory    The directory where the Doctrine_Record class will be written
     * @param  array  $models       Optional array of models to import
     *
     * @return void
     */
    public function importSchema($schema, $format = 'yml', $directory = null, $models = array())
    {
        $this->_validation['column'][] = 'title';
        $this->_validation['column'][] = 'log';
        $this->_validation['column'][] = 'can_import';

        $schema = (array) $schema;
        $builder = new Base_Doctrine_Import_Builder();
        $builder->setTargetPath($directory);
        $builder->setTargetPathMain($directory);
        $builder->setOptions($this->getOptions());

        $array = $this->buildSchema($schema, $format);

        $builder->setSchema($array);

        if (count($array) == 0) {
            throw new Doctrine_Import_Exception(
                sprintf('No ' . $format . ' schema found in ' . implode(", ", $schema))
            );
        }

        foreach ($array as $name => $definition) {
            if ( ! empty($models) && !in_array($definition['className'], $models)) {
                continue;
            }

            $builder->buildRecord($definition);
        }
    }


    /**
     * parseSchema
     *
     * A method to parse a Schema and translate it into a property array.
     * The function returns that property array.
     *
     * @param  string $schema   Path to the file containing the schema
     * @param  string $type     Format type of the schema we are parsing
     * @return array  $build    Built array of schema information
     */
    public function parseSchema($schema, $type)
    {
        $defaults = array('abstract'            =>  false,
            'className'           =>  null,
            'tableName'           =>  null,
            'connection'          =>  null,
            'relations'           =>  array(),
            'indexes'             =>  array(),
            'attributes'          =>  array(),
            'templates'           =>  array(),
            'actAs'               =>  array(),
            'options'             =>  array(),
            'package'             =>  null,
            'inheritance'         =>  array(),
            'detect_relations'    =>  false);

        $array = Doctrine_Parser::load($schema, $type);
        // Loop over and build up all the global values and remove them from the array
        $globals = array();
        foreach ($array as $key => $value) {
            if (in_array($key, self::$_globalDefinitionKeys)) {
                unset($array[$key]);
                $globals[$key] = $value;
            }
        }

        // Merge the globals that aren't specifically set to each class
        foreach ($array as $className => $table) {
            $array[$className] = Doctrine_Lib::arrayDeepMerge($globals, $array[$className]);
        }

        $build = array();

        foreach ($array as $className => $table) {
            $table = (array) $table;
            $this->_validateSchemaElement('root', array_keys($table), $className);

            $columns = array();

            $className = isset($table['className']) ? (string) $table['className']:(string) $className;

            if (isset($table['inheritance']['keyField']) || isset($table['inheritance']['keyValue'])) {
                $table['inheritance']['type'] = 'column_aggregation';
            }

            if (isset($table['tableName']) && $table['tableName']) {
                $tableName = $table['tableName'];
            } else {
                if (isset($table['inheritance']['type']) && ($table['inheritance']['type'] == 'column_aggregation')) {
                    $tableName = null;
                } else {
                    $tableName = Doctrine_Inflector::tableize($className);
                }
            }

            $connection = isset($table['connection']) ? $table['connection']:'current';

            $columns = isset($table['columns']) ? $table['columns']:array();


            $addService = !isset($table['columns']['id_service']);
            if(isset($table['options']['service'])){
                $addService = $table['options']['service'];
            }

            if($addService){
                $columns['id_service'] = array( 'type' => 'integer', 'log' => false );
            }



            if ( ! empty($columns)) {
                foreach ($columns as $columnName => $field) {

                    // Support short syntax: my_column: integer(4)
                    if ( ! is_array($field)) {
                        $original = $field;
                        $field = array();
                        $field['type'] = $original;
                    }

                    $colDesc = array();
                    if (isset($field['name'])) {
                        $colDesc['name'] = $field['name'];
                    } else {
                        $colDesc['name'] = $columnName;
                    }

                    $this->_validateSchemaElement('column', array_keys($field), $className . '->columns->' . $colDesc['name']);

                    // Support short type(length) syntax: my_column: { type: integer(4) }
                    $e = explode('(', $field['type']);
                    if (isset($e[0]) && isset($e[1])) {
                        $colDesc['type'] = $e[0];
                        $value = substr($e[1], 0, strlen($e[1]) - 1);
                        $e = explode(',', $value);
                        $colDesc['length'] = $e[0];
                        if (isset($e[1]) && $e[1]) {
                            $colDesc['scale'] = $e[1];
                        }
                    } else {
                        $colDesc['type'] = isset($field['type']) ? (string) $field['type']:null;
                        $colDesc['length'] = isset($field['length']) ? (int) $field['length']:null;
                        $colDesc['length'] = isset($field['size']) ? (int) $field['size']:$colDesc['length'];
                    }

                    $colDesc['fixed'] = isset($field['fixed']) ? (int) $field['fixed']:null;
                    $colDesc['primary'] = isset($field['primary']) ? (bool) (isset($field['primary']) && $field['primary']):null;
                    $colDesc['default'] = isset($field['default']) ? $field['default']:null;
                    $colDesc['autoincrement'] = isset($field['autoincrement']) ? (bool) (isset($field['autoincrement']) && $field['autoincrement']):null;

                    if (isset($field['sequence'])) {
                        if (true === $field['sequence']) {
                            $colDesc['sequence'] = $tableName;
                        } else {
                            $colDesc['sequence'] = (string) $field['sequence'];
                        }
                    } else {
                        $colDesc['sequence'] = null;
                    }

                    $colDesc['values'] = isset($field['values']) ? (array) $field['values']:null;

                    // Include all the specified and valid validators in the colDesc
                    $validators = Doctrine_Manager::getInstance()->getValidators();

                    foreach ($validators as $validator) {
                        if (isset($field[$validator])) {
                            $colDesc[$validator] = $field[$validator];
                        }
                    }

                    $colDesc['can_import'] = !isset($field['can_import']);
                    if(isset($field['can_import'])){
                        $colDesc['can_import'] = $field['can_import'];
                    }

                    if($columnName == 'id_service'){
                        $colDesc['log'] = false;
                        $colDesc['can_import'] = false;
                    }

//                    if($addService){
//                        $columns['id_service'] = array( 'type' => 'integer', 'log' => false );
//                    }


                    $columns[(string) $columnName] = $colDesc;
                }
            }

            // Apply the default values
            foreach ($defaults as $key => $defaultValue) {
                if (isset($table[$key]) && ! isset($build[$className][$key])) {
                    $build[$className][$key] = $table[$key];
                } else {
                    $build[$className][$key] = isset($build[$className][$key]) ? $build[$className][$key]:$defaultValue;
                }
            }

            $build[$className]['className'] = $className;
            $build[$className]['tableName'] = $tableName;
            $build[$className]['columns'] = $columns;

            // Make sure that anything else that is specified in the schema makes it to the final array
            $build[$className] = Doctrine_Lib::arrayDeepMerge($table, $build[$className]);

            // We need to keep track of the className for the connection
            $build[$className]['connectionClassName'] = $build[$className]['className'];

            $build[$className]['_path'] = realpath(dirname(dirname($schema))).'/models';
        }

        return $build;
    }

}