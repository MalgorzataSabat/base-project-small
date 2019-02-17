<?php

/**
 * Created by PhpStorm.
 * User: Robert Rogiński
 * Date: 2015-07-20
 * Time: 12:01
 */
class Layout_Form_Layout extends Base_Form_Horizontal
{
    /**
     * @var Layout
     */
    protected $_model;

    private $_layoutList = array();

    public function init()
    {
        $this->setElementsBelongTo('');
        $fields = array();
        $dataMap = array();

        $this->_layoutList = Layout::getList(array('coll_key' => 'id_layout'));
        $layoutOptions = array();
        foreach ($this->_layoutList as $layout){
            $layoutOptions[$layout['id_layout']] = $layout['name'];
        }

        $fields['name'] = $this->createElement( 'text', 'name', array(
            'label' => $this->_tlabel.'name',
            'required' => true,
            'allowEmpty' => false,
            'filters' => array('StringTrim'),
            'value' => $this->_model->getName(),
        ));

        $fields['id_layout_template'] = new Layout_Form_Element_LayoutTemplate('id_layout_template', array(
            'value' => $this->_model['id_layout_template'],
            'required' => true,
            'allowEmpty' => false,
            'decorators' => $this->_elementDecorators,
        ));

        $fields['is_default'] = $this->createElement( 'checkbox', 'is_default', array(
            'label' => $this->_tlabel.'is_default',
            'required' => false,
            'allowEmpty' => true,
            'class' => 'iCheck',
            'filters' => array('StringTrim'),
            'value' => $this->_model->getIsDefault(),
        ));

        $fields['is_public'] = $this->createElement( 'checkbox', 'is_public', array(
            'label' => $this->_tlabel.'is_public',
            'required' => false,
            'allowEmpty' => true,
            'class' => 'iCheck',
            'filters' => array('StringTrim'),
            'value' => $this->_model->getIsPublic(),
        ));

        $this->addDisplayGroup( $fields, 'main', array(
            'legend' => $this->_tlabel.'group-std',
            'style' => 'margin-bottom: 20px;',
        ));
        $gMain = $this->getDisplayGroup('main');


        $dataMap['data_map'] = new Layout_Form_Element_DataMap('data_map', array(
            'label' => $this->_tlabel.'placeholders',
            'value' => $this->_model->getDataMap(),
            'id_layout_template' => $this->_model['id_layout_template'],
        ));
        $this->addHtmlTag(array($dataMap['data_map']), array('class' => 'layout_data-map'));

        $this->addDisplayGroup( $dataMap, 'map', array(
            'legend' => $this->_tlabel.'group-map',
        ));


        $save = $this->createElement('button', 'submit', array(
            'label' => 'Zapisz',
            'icon' => 'save',
            'type' => 'submit'
        ));

        $this->setFormActions(array($save));
        $this->addElements(array($save));

        $this->addHtmlTag(array($gMain, $save), array('class' => 'well'));

    }
}