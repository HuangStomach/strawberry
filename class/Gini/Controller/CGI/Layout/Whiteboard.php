<?php

namespace Gini\Controller\CGI\Layout;

abstract class Whiteboard extends \Gini\Controller\CGI\Layout {

    function __preAction($action, &$params){
        $title = \Gini\Config::get('site.title') ? : '后台管理系统';
        $this->view = V('layout/whiteboard/layout', [
            'title' => $title
        ]);
    }

}
