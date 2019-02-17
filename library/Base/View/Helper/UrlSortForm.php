<?php
/**
 * Created by PhpStorm.
 * User: Dawid Przygodzki
 * Date: 2015-05-04
 * Time: 14:43
 */

/**
 * @deprecated
 * Class News_View_Helper_SortColumn
 */
class Base_View_Helper_UrlSortForm extends Zend_View_Helper_Abstract
{
    /**
     *
     * @param $filter Base_Form
     * @return string
     */
    public function urlSortForm($filter, $column)
    {
        $url = '';

        $filterVal = $filter->getValues(true);
        $direction = 'DESC';

        if($column == $filterVal['order'] && $filterVal['order_direction'] == 'DESC'){
            $direction = 'ASC';
        }

        $values = array(
            'order' => $column,
            'order_direction' => $direction
        );

        if(!empty($values)){
            $url = '?'.http_build_query($values);
        }


        return $url;
    }
}