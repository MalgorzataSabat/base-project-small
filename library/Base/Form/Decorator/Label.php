<?php
/**
 * Created by PhpStorm.
 * User: Dawid Przygodzki
 * Date: 2015-03-16
 * Time: 12:48
 */

class Base_Form_Decorator_Label extends Zend_Form_Decorator_Label
{

    /**
     * @param string $content
     * @return mixed
     */
    public function render($content)
    {
        $element = $this->getElement();

        $size = $element->getAttrib('label-size');
        if(empty($size)){
            $size = $this->getOption('size');
        }
        $this->removeOption('size');

        if($size){ $size = 'col-md-'.$size; }
        $class = trim($this->getOption('class').' '.$size);
        $this->setOption('class', $class);

        return parent::render($content);
    }


    public function getReqSuffix()
    {
        $rSuffix = parent::getReqSuffix();
        if(!empty($rSuffix)){
            return $rSuffix;
        }

        parent::setTagClass(parent::getTagClass() . ' require');
        return;
    }
} 