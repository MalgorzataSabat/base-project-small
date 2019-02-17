<?php

class Base_Form_Decorator_WrapElementFilter extends Zend_Form_Decorator_Abstract
{
    /**
     * Renders a form element decorating it with the Twitter's Bootstrap markup
     *
     * @param $content
     *
     * @return string
     */
    public function render($content)
    {
        $hasErrors = $this->getElement()->hasErrors();
        $attribs = $this->getOptions();

        $class = '';
        $style = '';
        $fieldName = '';
        $id = '';

        if ( isset( $attribs['style'] ) ) $style = $attribs['style'];
        if ( isset( $attribs['class'] ) ) $class = $attribs['class'];
        if ( isset( $attribs['id'] ) ) $id = 'id="'.$attribs['id'].'"';
        if ( isset( $attribs['data-field-name'] ) ) $fieldName = 'data-field-name="'.$attribs['data-field-name'].'"';

        $xhtml = '<div class="form-group' . (($hasErrors) ? ' has-error has-feedback' : '') . ' ' . $class . '"
            style="' . $style . '" '.$id.' '.$fieldName.'>
                <div class="form-group-filter">'
                    . $content .
                '</div>
                <div class="form-group-remove">
                    <a href="javascript:void(0);" class="btn btn-default remove-filter-field"><i class="fa fa-remove txt-color-red"></i></a>
                </div>
            </div>';

        return $xhtml;
    }
}
