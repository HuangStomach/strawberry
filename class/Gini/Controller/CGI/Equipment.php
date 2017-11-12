<?php

namespace Gini\Controller\CGI;

class Equipment extends \Gini\Controller\CGI\Layout\Home {

    function __index() {
        $form = $this->form('get');
        $tag = $form['tag'];

        $tags = those('equipment/tag');

        $equipments = those('equipment');
        if ($tag) $equipments->whose('tag')->contains($tag);
        $equipments->limit(0, 10);

        $this->view->active = 'equipment';
        $this->view->body = V('home/equipment/list', [
            'select' => $tag,
            'tags' => $tags,
            'equipments' => $equipments
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