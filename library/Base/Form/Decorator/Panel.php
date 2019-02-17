<?php

class Base_Form_Decorator_Panel extends Zend_Form_Decorator_Abstract
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
        $class = 'panel-heading';
        $attribs = $this->getOptions();
        $element = $this->getElement();
        $legend = $element->getAttrib('legend');


        if(isset($attribs['class'])){ $class = $attribs['class']; }

        if (!empty($legend)) {
            if (null !== ($translator = $element->getTranslator())) {
                $legend = $translator->translate($legend);
            }
        }


        $xhtml = '<div class="'.$class.'">
                    <h3 class="panel-title">'.$legend.'</h3>
                    <div class="panel-tools">
                        <a class="collapse" href="javascript:;"></a>
                        <a class="config" data-toggle="modal" href="#portlet-config"></a>
                        <a class="reload" href="javascript:;"></a>
                        <a class="remove" href="javascript:;"></a>
                    </div>
                </div>';


        return $xhtml . $content;
    }
}
