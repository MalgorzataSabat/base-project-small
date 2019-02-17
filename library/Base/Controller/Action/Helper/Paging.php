<?php
/**
 * Created by JetBrains PhpStorm.
 * User: marek
 * Date: 11.09.13
 * Time: 09:06
 * To change this template use File | Settings | File Templates.
 */

class Base_Controller_Action_Helper_Paging extends Zend_Controller_Action_Helper_Abstract {

    /**
     * @param $query Doctrine_Query
     * @param array $config [limit, page, pager_name, mode, pager_name, pages_name, pager-range_name, count_item, view]
     * @return Doctrine_Collection
     */
    public function paging($query, $config = array())
    {
        $view = isset($config['view']) ? $config['view'] : Base_Layout::getView();
        $limit = isset($config['limit']) ? $config['limit'] : 20;
        $page = isset($config['page']) ? $config['page'] : $this->getRequest()->getParam('page', 1);
        $pagerName = isset($config['pager_name']) ? $config['pager_name'] : 'pager';
        $pagesName = isset($config['pages_name']) ? $config['pages_name'] : 'pages';
        $pagerRangeName = isset($config['pager-range_name']) ? $config['pager-range_name'] : 'pagerRange';
        $hydrationMode = isset($config['mode']) ? $config['mode'] : Doctrine::HYDRATE_ARRAY;
        $countItem = isset($config['count_item']) ? true : false;

        $pager = new Doctrine_Pager($query, $page, $limit);
        $result = $pager->execute(array(), $hydrationMode);
        $pagerRange = new Doctrine_Pager_Range_Sliding( array('chunk' => 5), $pager );
        $pages = $pagerRange->rangeAroundPage();

        $view->$pagerName = $pager;
        $view->$pagesName = $pages;
        $view->$pagerRangeName = $pagerRange;
        $view->countItem = $countItem;

        return $result;
    }

    public function direct($query, $config = array())
    {
        return $this->paging($query, $config);
    }

}