<?php

namespace Gini\Controller\CGI;

class Index extends \Gini\Controller\CGI\Layout\Home {

    function __index() {
        $carousel = those('carousel');
        $links = those('link')->whose('type')->is(\Gini\ORM\Link::TYPE_FRIENDLY);

        $this->view->active = 'index';
        $this->view->header = V('home/index/carousel', [
            'carousel' => $carousel
        ]);

        $this->view->body = V('home/index/body', [
            'links' => $links
        ]);
    }

    function actionContact() {
        $this->view->active = 'contact';
        $this->view->body = V('home/contact');
    }

}