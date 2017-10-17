<?php

namespace Gini\Controller\CGI\Layout;

abstract class Whiteboard extends \Gini\Controller\CGI\Layout {

    protected $selected = null;

    function __preAction($action, &$params){
        $title = \Gini\Config::get('site.title') ? : '后台管理系统';
        $this->view = V('layout/whiteboard/layout', [
            'title' => $title
        ]);
    }

    function __postAction($action, &$params, $response) {
        $me = _G('ME');
        $route = \Gini\CGI::route();
        if ($route) $args = explode('/', $route);
        if (!$route || count($args) == 0) $args = ['index'];

        return parent::__postAction($action, $params, $response);
    }
}
