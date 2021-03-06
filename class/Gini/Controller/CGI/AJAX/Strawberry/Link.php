<?php

namespace Gini\Controller\CGI\AJAX\Strawberry;

class Link extends \Gini\Controller\CGI {

    public function actionDelete($id) {
        $link = a('link', $id);

        $view = V('admin/link/delete', [
            'link' => $link
        ]);
        
        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', $view);
    }

}
