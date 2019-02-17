<?php
/**
 * Created by PhpStorm.
 * User: Dawid Przygodzki
 * Date: 2015-05-04
 * Time: 14:43
 */

/**
 * @deprecated
 * Class Base_View_Helper_FilterQuery
 */
class Base_View_Helper_FilterQuery extends Zend_View_Helper_Abstract
{
    /**
     * @param $form Base_Form_Filter
     * @param array $options
     * @return string
     */
    public function filterQuery($form, $options = array())
    {
        $queryList = $form->getQueryList();
        $formName = get_class($form);

        $html = '<div class="btn-group">
            <div class="btn-group">
                <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-heart-o"></i>
                    '.$this->view->translate('label_query_select-query').'
                    <i class="fa fa-angle-down"></i>
                </button>';

                if($queryList){
                    $html.= '<ul class="dropdown-menu dropdown-menu-right">';
                        foreach($queryList as $query){
                            $html.= '<li><a href="?id_query='.$query['id_query'].'">';

                            if($query['is_default'] && $query['id_user'] == Base_Auth::getUserId()){
                                $html.= '<i class="fa fa-star txt-color-pink" rel="tooltip" data-placement="left" data-original-title="'.
                                    $this->view->translate('tooltip_query_is_default')
                                    .'"></i> ';
                            }

                            $html.= $this->view->escape($query['title']).'</a></li>';
                        }
                    $html.= '</ul>';
                }else{
                    $html.= '<div class="dropdown-menu dropdown-menu-right"><div class="alert alert-info no-margin no-padding">';
                        $html .= '<strong><i class="padding-5 fa fa-info"></i></strong> ';
                        $html.= $this->view->translate('label_query_select-filter_empty');
                    $html.= '</div></div>';
                }
            $html.= '</div>

            <div class="btn-group">
                <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-cog"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">';

                if(($id_query = $form->getIdQuery()) && $form->isFiltred() && $queryList[$id_query]['id_user'] == Base_Auth::getUserId())
                {
                    $html .= '<li><a href="'.Base::url('query_save', array('form' => $formName, 'id_query' => $id_query)).'"  data-query-save="1" class=" ajax">
                        <i class="fa fa-floppy-o"></i> '.$this->view->translate('label_save-query_exist').'</a>
                    </li>';
                }

                    $html .= '<li><a href="'.Base::url('query_new', array('form' => $formName)).'"
                        data-toggle="overbox"
                        data-type="ajax"><i class="fa fa-floppy-o"></i> '.$this->view->translate('label_save-query_option').'</a>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li><a href="'.Base::url('query', array('form' => $formName)).'"
                        data-toggle="overbox" data-type="ajax"
                        ><i class="fa fa-cog"></i> '.$this->view->translate('label_save-query_settings').'</a>
                </ul>
            </div>
        </div>';


        return $html;
    }
}