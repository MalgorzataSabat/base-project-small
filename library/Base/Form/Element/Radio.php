<?php

class Base_Form_Element_Radio extends Zend_Form_Element_Radio
{
    /**
     * The default separator (Changed to be nothing for bootstrap)
     * 
     * @var string
     */
    protected $_separator = '';

    /**
     * Remove all the default decorator for this element
     *
     * @return Twitter_Bootstrap_Form_Element_Radio
     */
    public function loadDefaultDecorators ()
    {
        return $this;
    }
}
