<?php

namespace Gini\Controller\CGI;

class Index extends \Gini\Controller\CGI\Layout\Home {

    function __index() {
        $carousel = those('carousel');
        $equipments = those('equipment')->whose('show')->is(true);
        $sites = those('site')->whose('show')->is(true);
        $links = those('link')->whose('type')->is(\Gini\ORM\Link::TYPE_FRIENDLY);

        $this->view->header = V('home/index/header', [
            'carousel' => $carousel
        ]);

        $this->view->body = V('home/index/body', [
            'equipments' => $equipments,
            'sites' => $sites,
            'links' => $links
        ]);
    }

    function actionContact() {
        $this->view->body = V('home/contact');
    }

}