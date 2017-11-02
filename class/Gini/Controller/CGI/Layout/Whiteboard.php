<?php

namespace Gini\Controller\CGI\Layout;

abstract class Whiteboard extends \Gini\Controller\CGI\Layout {

    function __preAction($action, &$params){
        $title = \Gini\Config::get('site.admin')['title'];
        $this->view = V('layout/whiteboard/layout', [
            'title' => $title
        ]);
    }

}
