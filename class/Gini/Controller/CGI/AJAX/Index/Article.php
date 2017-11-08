<?php

namespace Gini\Controller\CGI\AJAX\Index;

class Article extends \Gini\Controller\CGI {

    public function actionNotice() {
        $articles = those('article')->whose('type')->is(
            a('article/type')->whose('key')->is('notice')
        )->whose('active')->is(true)
        ->andWhose('date')->isLessThanOrEqual(date('Y-m-d H:i:s'))
        ->orderBy('date', 'desc')
        ->limit(0, 5);

        $view = V('home/index/notice', [
            'articles' => $articles
        ]);

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', $view);
    }

    public function actionWorks() {
        $article = those('article')->whose('type')->is(
            a('article/type')->whose('key')->is('works')
        )->whose('active')->is(true)
        ->andWhose('date')->isLessThanOrEqual(date('Y-m-d H:i:s'))
        ->orderBy('date', 'desc')
        ->current();
        
        $view = V('home/index/work', [
            'article' => $article
        ]);

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', $view);
    }
}