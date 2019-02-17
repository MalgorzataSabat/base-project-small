<?php

class Base_Form_DisplayGroup extends Zend_Form_DisplayGroup
{
    /**
     * Override the default decorators
     *
     * @return Base_Form_DisplayGroup
     */
    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
//            $boxClass = $this->getAttrib('class');
            $this->clearDecorators();

            $this->addDecorator('FormElements')
                ->addDecorator('Fieldset')
//                ->addDecorator(array('body' => 'HtmlTag'), array('tag' => 'div', 'class' => 'panel-body'))
//                ->addDecorator('Panel')
//                ->addDecorator(array('box' => 'HtmlTag'), array('tag' => 'div', 'class' => 'panel '.$boxClass))
            ;
        }

        return $this;
    }


    /**
     * Render display group
     *
     * @return string
     */
//    public function render(Zend_View_Interface $view = null)
//    {
//
//        $disposition = $this->getForm()->getDisposition();
//
//        /**
//         * Getting elements.
//         */
//        $elements = $this->getElements();
//
//        foreach ($elements as $eachElement) {
//            /**
//             * Removing label from buttons before render.
//             */
//            if ($eachElement instanceof Zend_Form_Element_Submit) {
//                $eachElement->removeDecorator('Label');
//            }
//
//            /**
//             * No decorators for hidden elements
//             */
//            if( $eachElement instanceof Zend_Form_Element_Hidden ) {
//                $eachElement->clearDecorators()->addDecorator('ViewHelper');
//            }
//
//            /**
//             * No decorators for hash elements
//             */
//            if( $eachElement instanceof Zend_Form_Element_Hash ) {
//                $eachElement->clearDecorators()->addDecorator('ViewHelper');
//            }
//
//
//            if($disposition == Base_Form::DISPOSITION_HORIZONTAL && false !== ($labelDecorator = $eachElement->getDecorator('Label'))){
//                $size = $eachElement->getAttrib('label-size');
//                if(empty($size)){ $size = 2; }
//
//                $class = $labelDecorator->getOption('class').' col-md-'.$size;
//                $labelDecorator->setOption('class', $class);
//            }
//        }
//
//
//
//        return parent::render($view);
//    }


}
