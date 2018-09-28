<?php

namespace Gini\Controller\CGI;

class Equipment extends \Gini\Controller\CGI\Layout\Home {

    function __index($start = 1, $step = 10) {
        $form = $this->form('get');
        $tag = H($form['tag']);

        $tags = those('equipment/tag');

        $equipments = those('equipment');
        if ($tag) $equipments->whose('tag')->contains($tag);
        
        $equipments->limit(((int)$start - 1) * (int)$step, (int)$step);
        
        $pagination = \Gini\Module\Widget::factory('pagination', [
            'hiddenDes' => true,
            'uri' => "equipment",
            'total' => $equipments->totalCount(),
            'start' => $start,
            'step' => $step,
            'form' => $form,
            'align' => 'center'
        ]);

        $this->view->active = 'equipment';
        $this->view->body = V('home/equipment/list', [
            'select' => $tag,
            'tags' => $tags,
            'equipments' => $equipments,
            'pagination' => $pagination
        ]);
    }

    function actionContent($id = 0) {
        $equipment = a('equipment', $id);
        if (!$equipment->id) $this->redirect('error/404');
        
        $this->view->body = V('home/equipment/content', [
            'equipment' => $equipment
        ]);
    }

}