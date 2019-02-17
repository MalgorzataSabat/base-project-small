<?php
/**
 * View helper definition
 *
 * @category Helpers
 * @package Twitter_Bootstrap_View
 * @subpackage Helper
 * @author Christian Soronellas <csoronellas@emagister.com>
 */

/**
 * Helper to generate a set of radio button elements
 *
 * @category Helpers
 * @package Twitter_Bootstrap_View
 * @subpackage Helper
 * @author Christian Soronellas <csoronellas@emagister.com>
 */
class User_View_Helper_FormRadioEmails extends Zend_View_Helper_FormRadio
{

    /**
     * Generates a set of radio button elements.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     *
     * @param mixed $value The radio value to mark as 'checked'.
     *
     * @param array $options An array of key-value pairs where the array
     * key is the radio value, and the array value is the radio text.
     *
     * @param array|string $attribs Attributes added to each radio.
     *
     * @return string The radio buttons XHTML.
     */
    public function formRadioEmails($name, $value = null, $attribs = null,
                                    $options = null, $listsep = "<br />\n")
    {
        $info = $this->_getInfo($name, $value, $attribs, $options, $listsep);
        extract($info); // name, value, attribs, options, listsep, disable

        $listsep = '';

        // retrieve attributes for labels (prefixed with 'label_' or 'label')
        $label_attribs = array();
        foreach ($attribs as $key => $val) {
            $tmp    = false;
            $keyLen = strlen($key);
            if ((6 < $keyLen) && (substr($key, 0, 6) == 'label_')) {
                $tmp = substr($key, 6);
            } elseif ((5 < $keyLen) && (substr($key, 0, 5) == 'label')) {
                $tmp = substr($key, 5);
            }

            if ($tmp) {
                // make sure first char is lowercase
                $tmp[0] = strtolower($tmp[0]);
                $label_attribs[$tmp] = $val;
                unset($attribs[$key]);
            }
        }

        $labelPlacement = 'append';
        foreach ($label_attribs as $key => $val) {
            switch (strtolower($key)) {
                case 'placement':
                    unset($label_attribs[$key]);
                    $val = strtolower($val);
                    if (in_array($val, array('prepend', 'append'))) {
                        $labelPlacement = $val;
                    }
                    break;
            }
        }

        $inline = false;
        if(isset($attribs['inline'])){
            $inline = (bool) $attribs['inline'];
            unset($attribs['inline']);

            if($inline){
                $label_attribs['class']= trim(@$label_attribs['class'].' '.$this->_inputType.'-inline');
            }
        }

        // the radio button values and labels
        $options = (array) $options;

        // build the element
        $xhtml = '';
        $list  = array();

        // should the name affect an array collection?
        $name = $this->view->escape($name);
        if ($this->_isArray && ('[]' != substr($name, -2))) {
            $name .= '[]';
        }

        // ensure value is an array to allow matching multiple times
        $value = (array) $value;

        // Set up the filter - Alnum + hyphen + underscore
        require_once 'Zend/Filter/PregReplace.php';
        $pattern = @preg_match('/\pL/u', 'a')
            ? '/[^\p{L}\p{N}\-\_]/u'    // Unicode
            : '/[^a-zA-Z0-9\-\_]/';     // No Unicode
        $filter = new Zend_Filter_PregReplace($pattern, "");

        // add radio buttons to the list.
        foreach ($options as $opt_value => $opt_label) {

            // Should the label be escaped?
            if ($escape) {
                $opt_label = $this->view->escape($opt_label);
            }

            // is it disabled?
            $disabled = '';
            if (true === $disable) {
                $disabled = ' disabled="disabled"';
            } elseif (is_array($disable) && in_array($opt_value, $disable)) {
                $disabled = ' disabled="disabled"';
            }

            // is it checked?
            $checked = '';
            if (in_array($opt_value, $value)) {
                $checked = ' checked="checked"';
            }

            // generate ID
            $optId = $id . '-' . $filter->filter($opt_value);

            // Wrap the radios in labels
            $delete = '';
            if(!$checked){
                $delete.= '<a href="'.Base::url('user_delete-email', array('email' => $this->view->escape($opt_value))).'"
                                data-text="'.$this->view->translate('label_modal-confirm_delete-user-email').'"
                                data-title="'.$this->view->escape($opt_value).'"
                                data-label="'.$this->view->translate('label_modal-confirm_label').'"
                                data-ajax="on"
                                data-ajax-remove="#wrap_'.$optId.'"
                                class="btn btn-xs btn-link confirmModal pull-left">
                                    <i class="glyphicon glyphicon-remove text-danger"></i>
                                </a>';
            }
            $delete.='<div class="clearfix"></div>';

            // Wrap the radios in labels
            $radio = '<label'
                . $this->_htmlAttribs($label_attribs) . '>'
                . (('prepend' == $labelPlacement) ? $opt_label : '')
                . '<input type="' . $this->_inputType . '"'
                . ' name="' . $name . '"'
                . ' id="' . $optId . '"'
                . ' value="' . $this->view->escape($opt_value) . '"'
                . $checked
                . $disabled
                . $this->_htmlAttribs($attribs)
                . $this->getClosingBracket()
                . (('append' == $labelPlacement) ? $opt_label : '')
                . '</label>'
                . $delete;

            // add to the array of radio buttons

            $radio_class = $this->_inputType;
            if($disable){
                $radio_class.= ' disabled';
            }

            if(!$inline){
                $radio = '<div class="'.$radio_class.'">'.$radio.'</div>';
            }

            $list[] = $radio;
        }

        // XHTML or HTML for standard list separator?


        if (!$this->_isXhtml() && false !== strpos($listsep, '<br />')) {
            $listsep = str_replace('<br />', '<br>', $listsep);
        }

        // done!
        $xhtml .= implode($listsep, $list);

        return $xhtml;
    }
}

