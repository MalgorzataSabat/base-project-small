<?php

class Base_Doctrine_Import_Builder extends Doctrine_Import_Builder
{


    /**
     * Path where to generated files
     *
     * @var string $_path_main
     */
    protected $_path_main = '';


    private $_schema = array();

    public function setSchema($schema)
    {
        $this->_schema = $schema;

        return $this;
    }


    /**
     * getTargetPath
     *
     * @return string       the path where imported files are being generated
     */
    public function getTargetPathMain()
    {
        return $this->_path_main;
    }


    /**
     * setTargetPath
     *
     * @param string path   the path where imported files are being generated
     * @return
     */
    public function setTargetPathMain($path_main)
    {
        if ($path_main) {
            $this->_path_main = $path_main;
        }
    }


    /**
     * buildRecord
     *
     * @param array $options
     * @param array $columns
     * @param array $relations
     * @param array $indexes
     * @param array $attributes
     * @param array $templates
     * @param array $actAs
     * @return void=
     */
    public function buildRecord(array $definition)
    {
        if ( ! isset($definition['className'])) {
            throw new Doctrine_Import_Builder_Exception('Missing class name.');
        }

        $definition['topLevelClassName'] = $definition['className'];

        if ($this->generateBaseClasses()) {
            $definition['is_package'] = (isset($definition['package']) && $definition['package']) ? true:false;

            if ($definition['is_package']) {
                $e = explode('.', trim($definition['package']));
                $definition['package_name'] = $e[0];

                $definition['package_path'] = ! empty($e) ? implode(DIRECTORY_SEPARATOR, $e):$definition['package_name'];
            }
            // Top level definition that extends from all the others
            $topLevel = $definition;
            unset($topLevel['tableName']);

            // If we have a package then we need to make this extend the package definition and not the base definition
            // The package definition will then extends the base definition
            $topLevel['inheritance']['extends'] = (isset($topLevel['package']) && $topLevel['package']) ? $this->_packagesPrefix . $topLevel['className']:$this->_baseClassPrefix . $topLevel['className'];
            $topLevel['no_definition'] = true;
            $topLevel['generate_once'] = true;
            $topLevel['is_main_class'] = true;
            unset($topLevel['connection']);

            // Package level definition that extends from the base definition
            if (isset($definition['package'])) {

                $packageLevel = $definition;
                $packageLevel['className'] = $topLevel['inheritance']['extends'];
                $packageLevel['inheritance']['extends'] = $this->_baseClassPrefix . $topLevel['className'];
                $packageLevel['no_definition'] = true;
                $packageLevel['abstract'] = true;
                $packageLevel['override_parent'] = true;
                $packageLevel['generate_once'] = true;
                $packageLevel['is_package_class'] = true;
                unset($packageLevel['connection']);

                $packageLevel['tableClassName'] = sprintf($this->_tableClassFormat, $packageLevel['className']);
                $packageLevel['inheritance']['tableExtends'] = isset($definition['inheritance']['extends']) ? sprintf($this->_tableClassFormat, $definition['inheritance']['extends']):$this->_baseTableClassName;

                $topLevel['tableClassName'] = sprintf($this->_tableClassFormat, $topLevel['topLevelClassName']);
                $topLevel['inheritance']['tableExtends'] = sprintf($this->_tableClassFormat, $packageLevel['className']);
            } else {
                $topLevel['tableClassName'] = sprintf($this->_tableClassFormat, $topLevel['className']);
                $topLevel['inheritance']['tableExtends'] = isset($definition['inheritance']['extends']) ? sprintf($this->_tableClassFormat, $definition['inheritance']['extends']):$this->_baseTableClassName;
            }

            $baseClass = $definition;
            $baseClass['className'] = $this->_getBaseClassName($baseClass['className']);
            $baseClass['abstract'] = true;
            $baseClass['override_parent'] = false;
            $baseClass['is_base_class'] = true;

            if(!empty($definition['_path'])){
                $this->setTargetPath($definition['_path']);
            }else{
                $this->setTargetPath($this->getTargetPathMain());
            }

            $this->writeDefinition($baseClass);


            if ( ! empty($packageLevel)) {
                $this->writeDefinition($packageLevel);
            }

            $this->writeDefinition($topLevel);
        } else {
            $this->writeDefinition($definition);
        }
    }



    /*
     * Build the phpDoc for a class definition
     *
     * @param  array  $definition
     */
    public function buildPhpDocs(array $definition)
    {
        $ret = array();

        $ret[] = $definition['className'];

        $ret[] = '';
        $ret[] = 'This class has been auto-generated by the Doctrine ORM Framework';
        $ret[] = '';

        if ((isset($definition['is_base_class']) && $definition['is_base_class']) || ! $this->generateBaseClasses()) {
            foreach ($definition['columns'] as $name => $column) {
                $name = isset($column['name']) ? $column['name']:$name;
                // extract column name & field name
                if (stripos($name, ' as '))
                {
                    if (strpos($name, ' as')) {
                        $parts = explode(' as ', $name);
                    } else {
                        $parts = explode(' AS ', $name);
                    }

                    if (count($parts) > 1) {
                        $fieldName = $parts[1];
                    } else {
                        $fieldName = $parts[0];
                    }

                    $name = $parts[0];
                } else {
                    $fieldName = $name;
                    $name = $name;
                }

                $name = trim($name);
                $fieldName = trim($fieldName);

                $ret[] = '@property ' . $column['type'] . ' $' . $fieldName;
            }

            $ret[] = '';
            $ret[] = '@method integer getId()';
            $ret[] = '@method boolean isNew()';
            $ret[] = '@method static '.ucfirst($definition['tableName']).' find($id, $tableName = null)';
            $ret[] = '@method static '.ucfirst($definition['tableName']).' findOneBy*';
            $ret[] = '@static Doctrine_Collection findBy*';
            $ret[] = '';

            foreach ($definition['columns'] as $name => $column) {
                $name = isset($column['name']) ? $column['name']:$name;
                $name = Base::lineToCamel(trim($name));
                $fieldName = trim($fieldName);

                $ret[] = '@method static get'.ucfirst($name).'ById() get'.ucfirst($name).'ById($id)';
                $ret[] = '@method get'.ucfirst($name).'() get'.ucfirst($name).'()';
                $ret[] = '@method set'.ucfirst($name).'() set'.ucfirst($name).'($value)';
            }

            $ret[] = '';

            if (isset($definition['relations']) && ! empty($definition['relations'])) {
                foreach ($definition['relations'] as $relation) {
                    $type = (isset($relation['type']) && $relation['type'] == Doctrine_Relation::MANY) ? 'Doctrine_Collection' : $this->_classPrefix . $relation['class'];
                    $ret[] = '@property ' . $type . ' $' . $relation['alias'];
                }
            }
            $ret[] = '';
        }

        $ret[] = '@package    ' . $this->_phpDocPackage;
        $ret[] = '@subpackage ' . $this->_phpDocSubpackage;
        $ret[] = '@author     ' . $this->_phpDocName . ' <' . $this->_phpDocEmail . '>';
        $ret[] = '@version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $';

        $ret = ' * ' . implode(PHP_EOL . ' * ', $ret);
        $ret = ' ' . trim($ret);

        return $ret;
    }

}