<?php

namespace Gini\Controller\CGI;

class Search extends \Gini\Controller\CGI\Layout\Home {

    function __index($start = 1, $step = 15) {
        $form = $this->form();
        $keyword = H($form['keyword']);
        
        $articles = those('article')->whose('title')->contains($keyword)
        ->andWhose('active')->is(true)
        ->andWhose('date')->isLessThanOrEqual(date('Y-m-d H:i:s'))
        ->orderBy('date', 'desc');

        $equipments = those('equipment')->whose('name')->contains($keyword)
        ->limit(0, 5);
        
        $articles->limit(((int)$start - 1) * (int)$step, (int)$step);
        
        $pagination = \Gini\Module\Widget::factory('pagination', [
            'hiddenDes' => true,
            'uri' => "search",
            'total' => $articles->totalCount(),
            'start' => $start,
            'step' => $step,
            'form' => $form,
            'align' => 'center'
        ]);

        $this->view->active = 'search';
        $this->view->body = V('home/search/list', [
            'keyword' => $keyword,
            'articles' => $articles,
            'equipments' => $equipments,
            'pagination' => $pagination
        ]);
    }

}