<?php

namespace Gini\Controller\CGI\AJAX\Strawberry;

class Article extends \Gini\Controller\CGI {

    public function actionDelete($id) {
        $article = a('article', $id);

        $view = V('admin/article/delete', [
            'article' => $article
        ]);
        
        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', $view);
    }

}