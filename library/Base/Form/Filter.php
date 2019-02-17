<?php

abstract class Base_Form_Filter extends Zend_Form
{
    /**
     * Wersja obsługi. Zmiana na 2 od dnia 2018-07-25
     * 1 - dotychczasowa obsuga
     * 2 - zmiany w sposobie przekazywnaia:
     * --- kolumn, opcji sortowania
     *
     * @var int
     */
    protected $_version = 1;

    protected $_filedName = '';

    protected $_forceClear = 0;

    /**1
     * Czy włączona jest obsługa wyboru kolumn oraz sortowania
     *
     * @var bool
     */
    protected $_tabColumnOn = true;

    /**
     * Możliwość sortowania po kolumnach, obsługiwane opcje
     * @var array
     */
    protected $_sortCols        = array();

    /**
     * Dostępne kolumny w tabeli
     * @var array
     */
    protected $_avalibleCols    = array();

    /**
     * Domyślne kolumny wyświetlane w tabeli
     * Kolumny/ustawienia systemowe
     *
     * @var array
     */
    protected $_defaultCols = array();

    /**
     * Pola wyszukiwarki/filtry
     * @var array
     */
    protected $_searchElements = array();

    /**
     * Pola wyszukiwarki, które są domyślnie wyświetlone
     * @var array
     */
    protected $_fieldsDisplay = array();

    protected $_prefixesInitialized = false;

    protected $_belong_to = '';

    protected $_tlabel = '';

    /**
     * Przechowywane są wartośc wyszukiwania. Potrzebne np:
     * do paginacji
     * @var Zend_Session_Namespace
     */
    protected  $_session;

    /**
     * Pole select, widoczne dla użytkownika, zawierające
     * listę możliwych filtrów wyszukiwania
     *
     * @var Base_Form_Element_Select
     */
    protected $_fieldAddElement;


    /**
     * Ukryte pole typu MultiSelect, zawierajaca listę
     * zaznaczonych filtów, wybranych przez użytkownika
     *
     * @var Base_Form_Element_Select
     */
    protected $_fieldOnElement;


    // ----------- OBSLUGA QUERY ------------
    private $_queryList; // lista query/kwerend/filtrów dostępnych dla użytkownika

    private $_queryDefault; // domyślne kwerenda dla użytkownika

    protected $_valuesData = array(); // dane dotyczące wartości oraz sterowania filtrem


    protected $optionsYesNo = array(
        '' => '',
        0 => 'label_no',
        1 => 'label_yes',
    );

    protected $_elementDefaultDecorators = array(
        array('ViewHelper'),
        array('InputGroup', array('class' => 'input-group')),
        array('ElementErrors'),
        array('Description', array('tag' => 'span', 'class' => 'help-block')),
        array('FieldSize', array('size' => 9)),
        array('Label', array('class' => 'control-label', 'size' => 3)),
        array('WrapElement')
    );

    protected $_elementSearchDecorators = array(
        array('ViewHelper'),
        array('InputGroup', array('class' => 'input-group')),
        array('ElementErrors'),
        array('Description', array('tag' => 'span', 'class' => 'help-block')),
        array('FieldSize', array('size' => 9)),
        array('Label', array('class' => 'control-label', 'size' => 3)),
        array('WrapElementFilter')
    );


    /**
     * Override the base form constructor.
     * @param null $options
     */
    public function __construct($options = null)
    {
        $this->_initializePrefixes();

        $this->setElementDecorators($this->_elementSearchDecorators);

        $this->setAttrib('novalidate', 'novalidate');

        if(empty($this->_belong_to)){
            $this->_belong_to = get_called_class();
        }
        $this->setElementsBelongTo($this->_belong_to);

        if(empty($this->_tlabel)){
            $this->_tlabel = strtolower(get_called_class()).'_';
        }

        if(empty($this->_filedName)){
            $this->_filedName = explode('_',strtolower(get_called_class()))[0].'.';
        }

        $this->setMethod('post');
        $this->setAction('?page=1');
        $this->setAttrib('class', trim($this->getAttrib('class').' form-horizontal form-filter'));
        $this->setAttrib('data-belong-to', $this->_belong_to);

        parent::__construct($options);

        $this->_loadQueryList();

        $this->createStandardElements();

        $this->_loadValues();

        $this->setDecorators(array(
            'FormElements',
            array('Form', array('role' => 'form')),
            array('FormFilter')
        ));
    }

    /**
     * Załadowanie danych do formularzy
     *
     * @throws Zend_Session_Exception
     */
    protected function _loadValues()
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $rParams = !$this->_forceClear ? $request->getQuery() : array();
        $_valuesIsSet = false;

        $requestValues = (array) $request->getParam($this->_belong_to, array());


        // zdefiniowanie pustych wartosci/struktury
        $this->_valuesData = array(
            'data' => $this->getValues($this->_belong_to),
            'id_query' => null,
            'is_filtred' => false,
        );

//        var_dump($this->getValues($this->_belong_to));
//        exit();

        // obsługa sesji
        if ( null === $this->_session ) {
            $this->_session = new Zend_Session_Namespace($this->_belong_to);
        }

        $clearAll = $request->getParam('clearAll');
        $clear = $request->getParam('clear', $this->_forceClear);

        if(!$this->_session->__isset('valuesData')){
            $this->_session->__set('valuesData', $this->_valuesData);
        }elseif($clear == '1'){
            $this->_session->__set('valuesData', $this->_valuesData);
        }elseif($clearAll == '1'){
            $this->_session->__set('valuesData', $this->_valuesData);
            $_valuesIsSet = true;
        }else{
            $_valuesIsSet = true; // wartości zostaną ustawione z sesji
        }


        $this->_valuesData = $this->_session->__get('valuesData');

        // pobranie danych z requestu
        if($requestValues){
            $_valuesIsSet = true;
            $this->_valuesData['data'] = $requestValues;
            $this->_valuesData['is_filtred'] = true;
        }

        // jeśli podane ID query
        $_idQuery = $request->getParam('id_query', 0);
        if($_idQuery && isset($this->_queryList[$_idQuery])) {
            $_valuesIsSet = true;

            $this->_valuesData['data'] = json_decode($this->_queryList[$_idQuery]['data'], true);
            $this->_valuesData['id_query'] = $_idQuery;
            $this->_valuesData['is_filtred'] = false;
        }

        // ustawienie domyslnej listy zdefiniowenej przez użytkownika
        if(!$_valuesIsSet && $this->_queryDefault){
            $_valuesIsSet = true;
            $this->_valuesData['data'] = json_decode($this->_queryDefault['data'], true);
            $this->_valuesData['id_query'] = $this->_queryDefault['id_query'];
            $this->_valuesData['is_filtred'] = false;
        }

        // ostatnie domyslne/systemowe ustawienie
        if(!$_valuesIsSet){
            $_valuesIsSet = true;
            $this->_valuesData['data']['field_on'] = $this->_fieldsDisplay;
            $this->_valuesData['data']['column'] = $this->_defaultCols;
            $this->_valuesData['id_query'] = null;
            $this->_valuesData['is_filtred'] = false;
        }

        // ręczne ustawienie parametrów z GET-a
        $manualGetCol = array();
        foreach($this->_valuesData['data'] as $k => $v){
            if(isset($rParams[$k])){
                $manualGetCol[] = $k;
                if(is_array($this->_valuesData['data'][$k])){
                    if(is_array($rParams[$k])){
                        $this->_valuesData['data'][$k] = $rParams[$k];
                    }else{
                        $this->_valuesData['data'][$k]['type'] = 'equal';
                        $this->_valuesData['data'][$k]['value'] = $rParams[$k];
                    }
                }else{
                    $this->_valuesData['data'][$k] = $rParams[$k];
                }
            }
        }

        if($clearAll && $manualGetCol){
            $this->_valuesData['data']['field_on'] = $manualGetCol;
            $this->_valuesData['data']['column'] = $this->_defaultCols;
        }

        $this->_session->__set('valuesData', $this->_valuesData);

        $this->populate($this->_valuesData['data']);
    }


    /**
     * Metoda tworzy standardowe elementy formularza, które są
     * identyczne dla wszystkich formularzy filtrów
     *
     * @throws Zend_Form_Exception
     */
    protected function createStandardElements()
    {
        foreach($this->_searchElements as $name => $field){
            $this->_searchElements[$name]->setDecorators($this->_elementSearchDecorators);
        }

        $fieldAddOptions = array();
        $fieldAllOptions = array();
        foreach($this->_searchElements as $fieldName => $element){
//            $fieldAllOptions[$fieldName] = $element->getLabel();// $this->getView()->translate($this->_tlabel.$fieldName);
            $fieldAllOptions[$fieldName] = $this->_getFieldName($fieldName);


            if(!in_array($fieldName, $this->_fieldsDisplay)){
                $fieldAddOptions[$fieldName] = $fieldAllOptions[$fieldName];
            }
        }

//        sort($fieldAllOptions);
//        sort($fieldAddOptions);
//        var_dump($fieldAllOptions);
//        exit();

        $this->_fieldAddElement = $this->createElement('select', 'field_add', array(
            'label' => 'filter-search_add-field',
            'decorators' => $this->_elementDefaultDecorators,
            'multiOptions' => $fieldAllOptions,
            'data-filter-fields' => json_encode($fieldAllOptions),
            'class' => 'form_field_add',
            'input-group' => array('pre' => array(
                'type' => 'button',
                'label' => '<i class="fa fa-plus txt-color-green"></i> dodaj',
                'class' => 'btn-default add-filter-field-button',
            )),
        ));

        $this->_fieldOnElement = $this->createElement('multiselect', 'field_on', array(
            'multioptions' => $fieldAllOptions,
            'decorators' => array('ViewHelper'),
            'class' => 'form_field_on',
            'style' => 'display: none;'
        ));


        $groupFilterSearchElements = $this->_searchElements;
        $groupFilterSearchElements['field_add'] = $this->_fieldAddElement;
        $groupFilterSearchElements['field_on'] = $this->_fieldOnElement;

        $this->addDisplayGroup($groupFilterSearchElements, 'filter-search');


        if($this->_tabColumnOn) {
            $avalibleColsOptions = array();

            if($this->_version == 3) {
                $avalibleColsOptions = array_combine($this->_avalibleCols,
                    array_map(array($this, "_getFieldName"), $this->_avalibleCols));
            }elseif($this->_version == 2){
                $avalibleColsOptions = array_combine(array_keys($this->_avalibleCols),
                    array_map(array($this, "_getFieldName"), array_keys($this->_avalibleCols)));
            }else{
                $avalibleColsOptions = $this->_avalibleCols;
            }


            $columns['column'] = $this->createElement('multiselect', 'column', array(
                'multiOptions' => $avalibleColsOptions,
                'decorators' => $this->_elementDefaultDecorators,
                'multiselectOrder' => true,
                'data-label-col-avalible' => $this->getView()->translate('label_multiselectOrder_col-avalible'),
                'data-label-col-selected' => $this->getView()->translate('label_multiselectOrder_col-selected'),
                'style' => 'height: 120px;',
                'validators' => array(
                    array('InArray', true, array(array_keys($avalibleColsOptions)))
                ),
                'size' => 12
            ));

//            var_dump($avalibleColsOptions);
//            exit();


            $optionOrder = array('' => '');
            if($this->_version == 3)
            {
                $optionOrder += array_combine($this->_sortCols,
                    array_map(array($this, "_getFieldName"), $this->_sortCols));
            }
            elseif($this->_version == 2)
            {
                foreach ($this->_sortCols as $key => $value) {
                    $optionOrder[$key] = $this->_getFieldName($key);
                }
            }
            else
            {
                foreach ($this->_sortCols as $key => $value) {
                    $optionOrder[$value] = $this->_getFieldName($key);
                }
            }

//            var_dump($optionOrder);
//            exit();

            $columns['order'] = $this->createElement('select', 'order', array(
                'label' => 'label_filed_order',
                'decorators' => $this->_elementDefaultDecorators,
                'multiOptions' => $optionOrder,
                'validators' => array(
                    array('InArray', true, array(array_keys($optionOrder)))
                ),
                'size' => 8,
                'label-size' => 4
            ));

            $optionOrderDirection = array(
                '' => '',
                'ASC' => $this->getView()->translate('label_order_direction_asc'),
                'DESC' => $this->getView()->translate('label_order_direction_desc'),
            );

            $columns['order_direction'] = $this->createElement('select', 'order_direction', array(
                'label' => 'label_filed_order_direction',
                'decorators' => $this->_elementDefaultDecorators,
                'multiOptions' => $optionOrderDirection,
                'size' => 8,
                'label-size' => 4
            ));


            $this->addDisplayGroup(array_merge($columns), 'filter-column');

            $groupFilterSearch = $this->getDisplayGroup('filter-search');
            $groupColumnSearch = $this->getDisplayGroup('filter-column');
            $this->addHtmlTag(array($groupFilterSearch), array('class' => 'tab-pane active', 'id' => 'search-filter'));
            $this->addHtmlTag(array($groupColumnSearch), array('class' => 'tab-pane', 'id' => 'search-column'));
            $this->addHtmlTag(array($groupFilterSearch, $groupColumnSearch), array('class' => 'tab-content'));

            $this->addHtmlTag(array($columns['column']), array('class' => 'col-lg-7 col-md-12'));
            $this->addHtmlTag(array($columns['order'], $columns['order_direction']), array('class' => 'col-lg-5 col-md-12'));
            $this->addHtmlTag(array_merge($columns), array('class' => 'row'));
        }


        $buttons['clear'] = $this->createElement('html', 'clear', array(
            'html' => '<a href="?clear=1" class="btn btn-default btn-sm">'.$this->getView()->translate('cms_button_clear').'</a>',
            'ignore' => true,
            'tag' => 'span'
        ));


        $buttons['submit'] = $this->createElement('submit', 'submit', array(
            'label' => 'cms_button_search',
            'type' => 'submit',
            'ignore' => true,
            'buttonType' => 'primary',
            'icon' => 'search',
            'class' => 'btn-sm',
        ));

        $buttons['clear']->setDecorators(array('ViewHelper'));
        $buttons['submit']->setDecorators(array('ViewHelper'));

        $this->addHtmlTag(array($buttons['clear'], $buttons['submit']), array('class' => 'widget-footer'));
        $this->addElements($buttons);
    }


    /**
     * @return bool
     */
    public function hasQuery()
    {
        return (bool) $this->_valuesData['id_query'];
    }

    /**
     * Metoda zwraca id akualnie wybranej kwerendy
     *
     * @return null|int
     */
    public function getIdQuery()
    {
        return $this->_valuesData['id_query'];
    }


    /**
     * Metoda zwraca informacje o akrualnie wybranej kwerendzie
     *
     * @param null $column
     * @return null
     */
    public function getQuery($column = null)
    {
        $id_query = $this->getIdQuery();
        if(!$id_query){
            return null;
        }

        if($column && isset($this->_queryList[$id_query][$column])){
            return $this->_queryList[$id_query][$column];
        }

        return $this->_queryList[$this->_valuesData['id_query']];
    }

    /**
     * Metoda zwraca in formację, czy wybrana kwerenda może zostać
     * wyczyszczona - wyczyszczenie spowoduje przrejście do domyślnej kwerendy
     *
     * @return bool
     */
    public function queryCanClear()
    {
        $query = $this->getQuery();
        if($query){

            if(!$query['is_default'] || $query['id_user'] != Base_Auth::getUserId()){
                return true;
            }
        }


        return false;
    }

    /**
     * Załadowanie listy kewrend do których dostęp
     * ma użytkownik
     */
    private function _loadQueryList()
    {
        $queryOptions = array('form' => $this->_getQueryFormClass());
        $this->_queryList = Query::getList($queryOptions);

        foreach($this->_queryList as $query){
            if($query['id_user'] == Base_Auth::getUserId() && $query['is_default']){
                $this->_queryDefault = $query;
            }
        }

        if(!$this->_queryDefault){
            foreach($this->_queryList as $query){
                if($query['is_public'] && $query['is_default']){
                    $this->_queryDefault = $query;
                }
            }
        }
    }

    protected function _getQueryFormClass()
    {
        return get_class($this);
    }


    /**
     * Metoda zwraca wszystkie kwerendy użytkownika
     *
     * @return mixed
     */
    public function getQueryList()
    {
        return $this->_queryList;
    }


    public function getValuesData()
    {
        return $this->_valuesData['data'];
    }


    /**
     * @return bool
     */
    public function isFiltred()
    {
        return $this->_valuesData['is_filtred'];
    }

    protected function _initializePrefixes()
    {
        if (!$this->_prefixesInitialized){
            $this->addPrefixPath('Base_Form_Element', 'Base/Form/Element', 'element');
            $this->addElementPrefixPath('Base_Form_Decorator', 'Base/Form/Decorator', 'decorator');
            $this->addDisplayGroupPrefixPath('Base_Form_Decorator', 'Base/Form/Decorator' );
            $this->addElementPrefixPath('Base_Validate', 'Base/Validate/', 'validate');
            $this->setDefaultDisplayGroupClass('Base_Form_DisplayGroup');

            $this->_prefixesInitialized = true;
        }
    }

    public function addHtmlTag($elements, $options = array(), $decorator_name = null)
    {
        $elements = array_values((array)$elements);

        $options_open = array_merge($options, array('openOnly' => true));
        $options_close = array_merge($options, array('closeOnly' => true));

        $decorator_name = empty($decorator_name) ? count($elements[0]->getDecorators()) : $decorator_name;
        $dec_open_name = 'open_'.$decorator_name;
        $dec_close_name = 'close_'.$decorator_name;

        $elements[0]->addDecorator(array($dec_open_name => 'HtmlTag'), $options_open);
        $elements[count($elements)-1]->addDecorator(array($dec_close_name => 'HtmlTag'), $options_close);

    }

    /**
     * @param $elements
     * @return $this
     */
    public function setFormActions($elements)
    {
        $elements = (array) $elements;
        foreach($elements as $el){
            $el->setDecorators(array('ViewHelper'));
        }

        $this->addHtmlTag($elements, array('class' => 'form-actions'));

        return $this;
    }


    public function populate(array $values)
    {
        $defaultValue = $this->getValues(true);
        $result = parent::populate($values);

        $fieldAddOptions = array();
        $fieldAllOptions = array();
        $fieldsOnValue = $this->field_on->getValue();
        foreach($this->_searchElements as $fieldName => $element){
//            $fieldAllOptions[$fieldName] = $element->getLabel();// $this->_tlabel.$fieldName;
            $fieldAllOptions[$fieldName] = $this->_getFieldName($fieldName);

            if(!in_array($fieldName, $fieldsOnValue)){
                $fieldAddOptions[$fieldName] = $fieldAllOptions[$fieldName];
            }
        }

//        sort($fieldAllOptions);
//        sort($fieldAddOptions);

        $this->field_add->setMultiOptions($fieldAddOptions);
        $this->field_add->setValue(null);

        foreach($this->_searchElements as $filedName => $element){
            if(!in_array($filedName, $fieldsOnValue)){
                $this->$filedName->setValue($defaultValue[$filedName]);
            }
        }


        if($this->getElement('column')) {

            if ($this->_version == 3) {

                $options = $this->column->getValue();
                $options = array_merge($options, array_diff($this->_avalibleCols, $options));
                $options = array_combine($options,
                    array_map(array($this, "_getFieldName"), $options));

                $this->column->setMultiOptions($options);
            }elseif ($this->_version == 2) {

                $options = array_flip($this->column->getValue());

                foreach($options as $k => $v){
                    if(isset($this->_avalibleCols[$k])){
                        $options[$k] = $this->_getFieldName($k);
                    }
                }

                $avalibleCols = array_combine(array_keys($this->_avalibleCols),
                    array_map(array($this, "_getFieldName"), array_keys($this->_avalibleCols)));
                $options += array_diff_key($avalibleCols, $options);


                $this->column->setMultiOptions($options);
            } else {
                $options = array_flip($this->column->getValue());

                foreach($options as $k => $v){
                    if(isset($this->_avalibleCols[$k])){
                        $options[$k] = $this->_avalibleCols[$k];

                    }
                }

                $options += array_diff_key($this->_avalibleCols, $options);

                $this->column->setMultiOptions($options);
            }
        }


        return $result;
    }


    /**
     * Render form
     *
     * @param  Zend_View_Interface $view
     * @return string
     */
    public function render(Zend_View_Interface $view = null)
    {
        $fieldAddOptions = $this->field_add->getMultiOptions();
        if(!$fieldAddOptions){
            $this->field_add->getDecorator('WrapElement')->setOption('style', 'display: none;');
        }

        $this->_fieldsDisplay = (array) $this->field_on->getValue();
        foreach($this->_searchElements as $name => $field){
            $wrapElement = $this->_searchElements[$name]->getDecorator('WrapElementFilter');
            $wrapElement->setOption('id', $this->_belong_to.'-'.$name);
            $wrapElement->setOption('data-field-name', $name);

            if($wrapElement){
                if(in_array($name, $this->_fieldsDisplay)){
                    $wrapElement->setOption('style', 'display: block;');
                }else{
                    $wrapElement->setOption('style', 'display: none;');
                }
            }
        }



        return parent::render($view);
    }


    /**
     * Metoda zwraca dane formularza w formacie pod zapytanie SQL
     *
     * @param bool $suppressArrayNotation
     * @return array
     */
    public function getValuesQuery($suppressArrayNotation = true)
    {
        $values = parent::getValues($suppressArrayNotation);

        if(!$this->isValid($values)){
            if(isset($this->_valuesData['data'])){
                $this->populate($this->_valuesData['data']);
                $values = $this->_valuesData['data'];
            }
        }

        if(isset($values['order']) && isset($this->_sortCols[$values['order']])){

            $values['order'] = $this->_sortCols[$values['order']];
            if(in_array($values['order_direction'], array('ASC', 'DESC'))){
                $values['order'].= ' '.$values['order_direction'];
            }

            unset($values['order_direction']);

        // nie stosować już poniższej obsługi
        }elseif(isset($values['order']) && isset($values['order_direction']) && array_search($values['order'], $this->_sortCols) !== false && in_array($values['order_direction'], array('ASC', 'DESC')))
        {
            if (isset($values['order']) && isset($values['order_direction'])) {
                $values['order'] = trim($values['order'] . ' ' . $values['order_direction']);
                unset($values['order_direction']);
            }
        }else{
            unset($values['order']);
            unset($values['order_direction']);
        }


        return $values;
    }

    public function getSortCols()
    {
        return $this->_sortCols;
    }

    public function _getFieldName($fieldName)
    {
        if($this->_version == 3 || $this->_version == 2){
            return Base::getFiledName($this->_filedName.$fieldName);
        }

        return $this->getView()->translate($this->_tlabel.$fieldName);
    }

    public function getSigId()
    {
        return get_class($this).'-'.md5(json_encode($this->getValue('column')));
    }

    public function getFieldView($column)
    {
        $v = explode('.', $this->_avalibleCols[$column]);

        if(count($v) == 2){
            return $v[0].'/_'.$v[1].'.phtml';
        }

        return null;
    }

}
