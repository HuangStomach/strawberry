<?php

namespace Gini\Controller\CGI;

class Article extends \Gini\Controller\CGI\Layout\Home {

    function __index($type = 'notice', $start = 1, $step = 15) {
        $articleType =  a('article/type')->whose('key')->is($type);
        $articles = those('article')->whose('type')->is($articleType);
        
        $articles->limit(($start - 1) * $step, $step);
        
        $pagination = \Gini\Module\Widget::factory('pagination', [
            'hiddenDes' => true,
            'uri' => "article/{$type}",
            'total' => $articles->totalCount(),
            'start' => $start,
            'step' => $step,
            'form' => $form,
            'align' => 'center'
        ]);

        $this->view->body = V('home/article/common', [
            'primary' => $articleType->name,
            'secondary' => $articleType->translate(),
            'articles' => $articles,
            'pagination' => $pagination
        ]);
    }

    function actionWorks($start = 1, $step = 15) {
        $articleType =  a('article/type')->whose('key')->is('works');
        $articles = those('article')->whose('type')->is($articleType);
        
        $articles->limit(($start - 1) * $step, $step);
        
        $pagination = \Gini\Module\Widget::factory('pagination', [
            'hiddenDes' => true,
            'uri' => "article/works",
            'total' => $articles->totalCount(),
            'start' => $start,
            'step' => $step,
            'form' => $form,
            'align' => 'center'
        ]);

        $this->view->body = V('home/article/works', [
            'primary' => $articleType->name,
            'secondary' => $articleType->translate(),
            'articles' => $articles,
            'pagination' => $pagination
        ]);
    }

}