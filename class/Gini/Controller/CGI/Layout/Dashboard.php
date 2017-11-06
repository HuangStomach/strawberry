<?php

namespace Gini\Controller\CGI\Layout;

abstract class Dashboard extends \Gini\Controller\CGI\Layout {

    protected $selected = null;

    function __preAction($action, &$params){
        $me = _G('ME');
        if (!$me->id) $this->redirect('admin/login');

        $title = \Gini\Config::get('site.title')['admin'];

        $items = \Gini\Config::get('sidebar.items');
        $route = \Gini\CGI::route();
        if ($route) $args = explode('/', $route);
        if (!$route || count($args) == 0) $args = ['index'];

        $sidebar = V('layout/dashboard/sidebar', [
            'title' => $title,
            'items' => $items,
            'active' => "admin/{$args[1]}",
        ]);

        $header = V('layout/dashboard/header', [
            
        ]);

        $this->view = V('layout/dashboard/layout', [
            'title' => $title,
            'header' => $header,
            'sidebar' => $sidebar,
        ]);
    }

}
