<?php

class Base_Form_Decorator_WrapElement extends Zend_Form_Decorator_Abstract
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
        $id = '';

        if ( isset( $attribs['style'] ) ) $style = $attribs['style'];
        if ( isset( $attribs['class'] ) ) $class = $attribs['class'];
        if ( isset( $attribs['id'] ) ) $id = 'id="'.$attribs['id'].'"';

        $xhtml = '<div class="form-group' . (($hasErrors) ? ' has-error has-feedback' : '') . ' ' . $class . '"
            style="' . $style . '" '.$id.'>'
            . $content .
            '</div>';

        return $xhtml;
    }
}
