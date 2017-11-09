<?php

namespace Gini\Controller\CGI;

class Equipment extends \Gini\Controller\CGI\Layout\Home {

    function __index() {
        $equipments = those('equipment')->limit(1, 10);

        $this->view->active = 'equipment';
        $this->view->body = V('home/equipment/list', [
            'equipments' => $equipments
        ]);
    }

}