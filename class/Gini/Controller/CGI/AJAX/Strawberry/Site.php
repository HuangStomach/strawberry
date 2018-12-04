<?php

namespace Gini\Controller\CGI\AJAX\Strawberry;

class Site extends \Gini\Controller\CGI {

    public function actionDelete($id) {
        $site = a('site', $id);

        $view = V('admin/site/delete', [
            'site' => $site
        ]);
        
        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', $view);
    }

}
