<?php


class Base_View_Helper_FormDateTimePicker extends Zend_View_Helper_FormElement {

    private $_dateTimeFormat = array(
        'date' => array(
            'sideBySide' => true,
            'locale' => 'pl',
            'format' => 'YYYY-MM-DD',
            'language' => 'pl',
            'pickTime' => false,
        ),
        'time' => array(
            'sideBySide' => true,
            'locale' => 'pl',
            'format' => 'HH:mm',
            'language' => 'pl',
            'pickDate' => false,
        )
    );

    public function formDateTimePicker($name, $value=null, $attribs=null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable
        $datetime_option = false;

        // build the element
        $disabled = '';
        if ($disable) {
            // disabled
            $disabled = ' disabled="disabled"';
        }

        $attribs['class'] = trim(@$attribs['class']);


        // end tag depend of doctype
        $endTag = ' />';

        if ( ($this->view instanceof Zend_View_Abstract) &&  !$this->_isXhtml()) {
            $endTag = '>';
        }

        $displayIcon = isset($attribs['display-icon']) ? $attribs['display-icon'] : true;
        $addon_icon = isset($attribs['addon-icon']) && strlen($attribs['addon-icon']) > 0 ? $attribs['addon-icon'] : 'calendar';

        $iconHtml = '';
        if($displayIcon){
            $iconHtml = '<span class="input-group-addon"><span class="fa fa-' . $addon_icon . '"></span></span>';
        }

        $xhtml = '
        <div class="input-group date form_time '.$this->view->escape($id).'">
            <input type="text" id="'.$this->view->escape($id).'" name="'.$this->view->escape($name).'" value="'.$this->view->escape($value).'" ' . $disabled . $this->_htmlAttribs($attribs) . $endTag
        .$iconHtml.
        '</div>';

        if(array_key_exists('datetime-format', $attribs) && isset($this->_dateTimeFormat[$attribs['datetime-format']])){
            $datetime_option = json_encode($this->_dateTimeFormat[$attribs['datetime-format']]);
            unset($attribs['datetime-format']);
        }

        if ( !$datetime_option && array_key_exists('datetime', $attribs) ) {
            $datetime_option = json_encode((array) $attribs['datetime']);
            unset($attribs['datetime']);
        }

        if ( $datetime_option ) {
            $this->view->headScript()->appendScript("$('#".$this->view->escape($id)."').datetimepicker(".$datetime_option.")");
        }
        else
        {
            $this->view->headScript()->captureStart();
            echo "
                $('#".$this->view->escape($id)."').datetimepicker(
                    {
                        format: 'YYYY-MM-DD HH:mm',
                        language: 'pl',
                    }
                );
            ";
            $this->view->headScript()->captureEnd();
        }

        return $xhtml;
    }
}