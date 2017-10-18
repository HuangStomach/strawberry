<?php

namespace Gini\Controller\CGI;

class User extends Layout\Dashboard {

    function __index() {
        $item = \Gini\Config::get('sidebar.items')['user'];
        $users = those('user');
        $this->view->body = V('user/list', [
            'item' => $item,
            'users' => $users,
        ]);
    }

}