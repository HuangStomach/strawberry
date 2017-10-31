<?php

namespace Gini\Controller\CGI\AJAX\Admin;

class Site extends \Gini\Controller\CGI {

    public function actionDelete($id) {
        $site = a('site', $id);

        $view = V('site/delete', [
            'site' => $site
        ]);
        
        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', $view);
    }

}