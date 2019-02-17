<?php


class Base_View_Helper_UrlFilterForm extends Zend_View_Helper_Abstract
{

    public function urlFilterForm($filter, $column, $value)
    {
        $url = '';

        $filterVal = $filter->getValues(true);

        $filterVal[$column] = $value;

        $values[$filter->getElementsBelongTo()] = $filterVal;

        if ( !empty($values) ) {
            $url = '?' . http_build_query($values);
        }

        return $url;
    }
}