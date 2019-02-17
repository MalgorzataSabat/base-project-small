<?php
/**
 * Created by PhpStorm.
 * User: Robert Rogiński
 * Date: 08.05.14
 * Time: 10:18
 */
class Base_Form_Decorator_InputGroup extends Zend_Form_Decorator_Abstract
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
        $input_group = $this->getElement()->getAttrib('input-group');
        $searchable = $this->getElement()->getAttrib('searchable');
        $attribs = $this->getOptions();

        if(!empty($input_group) || !empty($searchable))
        {

            if(isset($input_group['pre']) && !empty($input_group['pre'])){
                $content = $this->_getHtmlInputGroup($input_group['pre']) . $content;
            }

            if(isset($input_group['post']) && !empty($input_group['post'])){
                $content = $content . $this->_getHtmlInputGroup($input_group['post']);
            }


            $class = trim($attribs['class'].' '.@$input_group['class']);
            $style = isset($attribs['style']) ? $style = trim($attribs['style']) : '';
            $content = '<div class="'.$class.'" style="'.$style.'">'.$content.'</div>';
        }

        return $content;
    }

    private function _getHtmlInputGroup($data)
    {
        $html = '';
        $view = $this->getElement()->getView();
        $elementAttribs = $this->getElement()->getAttribs();

        if(is_array($data))
        {
            $type  = isset($data['type'])  ? $data['type'] : 'text';
            $label = isset($data['label']) ? $data['label'] : '';
            $class = isset($data['class']) ? $data['class'] : '';
            unset($data['type']);
            unset($data['label']);
            unset($data['class']);
        }
        else
        {
            $type  = 'text';
            $label = $data;
            $class = isset($data['class']) ? $data['class'] : '';
        }

        if($type == 'text')
        {
            $addon_class = 'input-group-addon';
            $html = '<span class="'.$addon_class.'">'.$label.'</span>';
        }
        elseif($type == 'button')
        {
            $class = $class ? $class : 'btn-default';
            $addon_class = 'input-group-btn';
            $html = '<span class="'.$addon_class.'">
                            <button class="btn '.$class.'" type="button">
                                '. $label .'
                            </button>
                         </span>';
        }
        elseif ($type == 'icon') {
            $addon_class = 'input-group-addon ';
            $icon_class = 'fa '.$class;
            $html = '<span class="'.$addon_class.'">';
            $html .= '<i class="'.$icon_class.'"></i>';
            $html .= '</span>';
        }
        elseif ( $type == 'href' ) {
            $class = $class ? $class : 'btn-default';
            $addon_class = 'input-group-btn';
            $data['data-target'] = '#'.$elementAttribs['id'];
            $attrHtml = $this->_htmlAttribs($data);

            $html = '<span class="'.$addon_class.'">
                        <a class="initAddNewRecord btn btn-sm '.$class.'" '.$attrHtml.'>
                            '.$label.'
                        </a>
                    </span>';
        }
        elseif($type == 'share_point')
        {
            $addon_class = 'input-group-btn';
            $class = $class ? $class : 'btn-default';
            $href = isset($data['href']) ? $data['href'] : Base::url('admin_cms-share-elements');
            $fancybox_type = isset($data['fancybox-type']) ? $data['fancybox-type'] : 'iframe';

            $html = '<span class="'.$addon_class.'">
                        <a data-fancybox-type="'.$fancybox_type.'" href="'.$href.'" class="btn '.$class.' initSharePointField">
                                '. $label .'
                        </a>
                     </span>';

            $view->headScript()->appendFile('/assets/js/sharePoint.js');
            $view->headLink()->appendStylesheet('/assets/lib/fancybox/source/jquery.fancybox.css');
            $view->headScript()->appendFile('/assets/lib/fancybox/source/jquery.fancybox.js');
        }

        return $html;
    }


    /**
     * Convert options to tag attributes
     *
     * @return string
     */
    protected function _htmlAttribs(array $attribs)
    {
        $_attribs = array();
        $view = Base_Layout::getView();
        foreach ((array) $attribs as $key => $val) {
            $_attribs[] = $key.'="'.$view->escape($val).'"';
        }

        return join(' ', $_attribs);
    }


}
