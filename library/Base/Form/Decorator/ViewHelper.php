<?php
require_once 'Zend/Form/Decorator/ViewHelper.php';

class Base_Form_Decorator_ViewHelper extends Zend_Form_Decorator_ViewHelper
{


    /**
     * Render an element using a view helper
     *
     * Determine view helper from 'viewHelper' option, or, if none set, from
     * the element type. Then call as
     * helper($element->getName(), $element->getValue(), $element->getAttribs())
     *
     * @param  string $content
     * @return string
     * @throws Zend_Form_Decorator_Exception if element or view are not registered
     */
    public function render($content)
    {
        $element = $this->getElement();

        $view = $element->getView();
        if (null === $view) {
            require_once 'Zend/Form/Decorator/Exception.php';
            throw new Zend_Form_Decorator_Exception('ViewHelper decorator cannot render without a registered view object');
        }

        if (method_exists($element, 'getMultiOptions')) {
            $element->getMultiOptions();
        }

        $helper        = $this->getHelper();
        $separator     = $this->getSeparator();
        $value         = $this->getValue($element);
        $attribs       = $this->getElementAttribs();
        $name          = $element->getFullyQualifiedName();
        $id            = $element->getId();
        $attribs['id'] = $id;
        $elementContent = '';

        if(in_array($helper, array('formText', 'formSelect', 'formPassword', 'formTextarea', 'formWysiwyg',
            'formDateTimePicker', 'formSearchNumber', 'formSearchDate'
        ))){
            $attribs['class'] = trim( (isset($attribs['class']) ? $attribs['class'] : '').' form-control');
        }

        unset($attribs['input-group']);
        unset($attribs['size']);
        unset($attribs['label-size']);

        $helperObject  = $view->getHelper($helper);
        if (method_exists($helperObject, 'setTranslator')) {
            $helperObject->setTranslator($element->getTranslator());
        }

        if(isset($attribs['searchable']) && $attribs['searchable']){
            $name.= '[value]';
            $value = isset($value['value']) ? $value['value'] : $value;
            $elementContent.= $this->_searchableFields();
        }


        // Check list separator
        if (isset($attribs['listsep'])
            && in_array($helper, array('formMulticheckbox', 'formRadio', 'formSelect'))
        ) {
            $listsep = $attribs['listsep'];
            unset($attribs['listsep']);

            $elementContent.= $view->$helper($name, $value, $attribs, $element->options, $listsep);
        } else {
            $elementContent.= $view->$helper($name, $value, $attribs, $element->options);
        }

        switch ($this->getPlacement()) {
            case self::APPEND:
                return $content . $separator . $elementContent;
            case self::PREPEND:
                return $elementContent . $separator . $content;
            default:
                return $elementContent;
        }
    }


    private function _searchableFields()
    {
        $element = $this->getElement();
        $helper = $this->getHelper();
        $attribs = $this->getElementAttribs();
        $name   = $element->getFullyQualifiedName().'[type]';
        $searchableType = null;
        $elementValue = $this->getValue($element);
        $valueType = isset($elementValue['type']) ? $elementValue['type'] : null;
        $view = $element->getView();
        $html = '';


        if(isset($attribs['searchable']['type'])){
            $searchableType = $attribs['searchable']['type'];
        }

        if(!$searchableType){
            if($helper == 'formSelect'){ $searchableType = 'select'; }
            if($helper == 'formText'){ $searchableType = 'text'; }

        }

        if(!$searchableType){
            return '';
        }

        $searchableOptions = array(
            'select' => array(
                'default' => 'equal',
                'options' => array(
                    'empty' => '<strong class="txt-color-blue">[ ]</strong> '.$view->translate('filter_search-type_empty'),
                    'equal' => '<strong class="txt-color-greenLight">==</strong> '.$view->translate('filter_search-type_equal'),
                    'diff' => '<strong class="txt-color-red"><></strong> '.$view->translate('filter_search-type_diff'),
                )
            ),
            'dictionary' => array(
                'default' => 'all',
                'options' => array(
                    'all' => '<strong class="txt-color-blue"><i class="fa fa-bars"></i></strong> '.$view->translate('filter_search-type_all'),
                    'empty' => '<strong class="txt-color-blue">[ ]</strong> '.$view->translate('filter_search-type_empty'),
                    'equal' => '<strong class="txt-color-greenLight">==</strong> '.$view->translate('filter_search-type_equal'),
                    'diff' => '<strong class="txt-color-red"><></strong> '.$view->translate('filter_search-type_diff'),
                    'open' => '<strong class="txt-color-greenLight"><i class="fa fa-circle-o"></i></strong> '.$view->translate('filter_search-type_open'),
                    'close' => '<strong class="txt-color-red"><i class="fa fa-circle"></i></strong> '.$view->translate('filter_search-type_close'),
                )
            ),
            'text' => array(
                'default' => 'contain',
                'options' => array(
                    'contain' => '<strong class="txt-color-teal">...</strong> '.$view->translate('filter_search-type_contain'),
                    'not-contain' => '<strong class="txt-color-red"><></strong> '.$view->translate('filter_search-type_not-contain'),
                    'equal' => '<strong class="txt-color-greenLight">==</strong> '.$view->translate('filter_search-type_equal'),
                    'start' => '<strong class="txt-color-greenLight">[..</strong> '.$view->translate('filter_search-type_start'),
                    'end' => '<strong class="txt-color-greenLight">..]</strong> '.$view->translate('filter_search-type_end'),
                    'empty' => '<strong class="txt-color-blue">[ ]</strong> '.$view->translate('filter_search-type_empty'),
                )
            ),
        );

        if(empty($valueType)){
            $valueType = $searchableOptions[$searchableType]['default'];
        }

        $defaultName = $searchableOptions[$searchableType]['options'][$valueType];

        $html.= '<div class="input-group-btn">
            <button id="dLabel" type="button" class="btn btn-default dropdown-toggle text-left" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 130px;">
                <span class="search-value">'.$defaultName.'</span> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu filter-field-search-type" aria-labelledby="dLabel">';

        foreach($searchableOptions[$searchableType]['options'] as $k => $v){
            $html.= '<li><a href="javascript:void(0)" data-value="'.$k.'">'.$v.'</a></li>';
        }

        $html.= '</ul>';
        $html.= $view->formHidden($name, $valueType);
        $html.= '</div>';


        return $html;
    }

}
