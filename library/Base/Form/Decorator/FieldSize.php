<?php

class Base_Form_Decorator_FieldSize extends Zend_Form_Decorator_Abstract
{
    /**
     * @param string $content
     * @return mixed
     */
    public function render($content)
    {
        $class = $this->getOption('class');
        $style = $this->getOption('style');

        $element = $this->getElement();
        $offset = $element->getAttrib('offset');
        $size = $element->getAttrib('size');
        if(empty($size)){
            $size = $this->getOption('size');
        }

        if($size){ $size = 'col-md-'.$size; }
        if($style){ $style = 'style="'.$style.'"'; }
        if($offset){ $offset = 'col-md-offset-'.$offset; }

        $class = trim($class.' '.$size.' '.$offset);

        return '<div class="'.$class.'" '.$style.'>'.$content.'</div>';
    }
}
