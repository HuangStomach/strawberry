<?php

namespace Gini\Controller\CGI\Layout;

abstract class Home extends \Gini\Controller\CGI\Layout {

    protected $selected = null;

    function __preAction($action, &$params){
        $title = \Gini\Config::get('site.title')['home'];

        $this->view = V('layout/home/layout', [
            'title' => $title,
            'header' => $header,
            'footer' => $footer,
        ]);
    }

}
