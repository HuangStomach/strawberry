<?php

namespace Gini\Controller\CGI;

class Article extends Layout\Dashboard {

    function __index() {
        $item = \Gini\Config::get('sidebar.items')['article'];
        $this->view->body = V('article/list', [
            'item' => $item,
        ]);
    }

}