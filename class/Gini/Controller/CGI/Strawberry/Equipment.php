<?php

namespace Gini\Controller\CGI\Strawberry;

class Equipment extends \Gini\Controller\CGI\Layout\Dashboard {
    
    protected $item;
        
    function __preAction($action, &$params) {
        $this->item = \Gini\Config::get('sidebar.items')['equipment'];
        parent::__preAction($action, $params);
    }

    function __index($start = 1, $step = 20) {
        $equipments = those('equipment');
        $form = $this->form('get');
        
        if ($form['keyword']) {
            $keyword = $form['keyword'];
            $equipments->whose('name')->contains($keyword);
        }

        $equipments->limit(($start - 1) * $step, $step);
        
        $pagination = \Gini\Module\Widget::factory('pagination', [
            'uri' => 'strawberry/equipment',
            'total' => $equipments->totalCount(),
            'start' => $start,
            'step' => $step,
            'form' => $form
        ]);

        $this->view->body = V('admin/equipment/list', [
            'item' => $this->item,
            'form' => $form,
            'equipments' => $equipments,
            'pagination' => $pagination
        ]);
    }

}