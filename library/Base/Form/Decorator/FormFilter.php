<?php

/** Zend_Form_Decorator_Abstract */
require_once 'Zend/Form/Decorator/Abstract.php';

class Base_Form_Decorator_FormFilter extends Zend_Form_Decorator_Abstract
{
    /**
    /**
     * Render a form
     *
     * Replaces $content entirely from currently set element.
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        $form = $this->getElement();
        $view = Base_Layout::getView();


        $xhtml = '
            <div class="jarviswidget filter-search ';
                if($form->isFiltred()):
                    $xhtml.= 'isFiltred';
                endif;
        $xhtml.= '" role="widget">';

        $xhtml.= '
            <header role="heading">
                <ul class="nav nav-tabs pull-left in">
                    <li class="active">
                        <a href="#search-filter" data-toggle="tab" aria-expanded="false">
                            <i class="fa fa-filter"></i>
                            <span class="hidden-mobile hidden-tablet">'.$view->translate('label_form-filter_tab-filters').'</span>
                        </a>
                    </li>';

                    if($form->getDisplayGroup('filter-column')):
                        $xhtml.= '
                            <li>
                                <a href="#search-column" data-toggle="tab" aria-expanded="true">
                                    <i class="fa fa-table"></i>
                                    <span class="hidden-mobile hidden-tablet">'.$view->translate('label_form-filter_tab-columns').'</span>
                                </a>
                            </li>';
                    endif;
        $xhtml.= '</ul>
            </header>';


        $xhtml.= '<div role="content">
            <div class="widget-body">';


        $xhtml.= $content;

        $xhtml.= '</div></div></div>';


        return $xhtml;
    }
}
