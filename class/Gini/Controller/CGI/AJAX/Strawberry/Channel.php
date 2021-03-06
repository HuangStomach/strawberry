<?php

namespace Gini\Controller\CGI\AJAX\Strawberry;

class Channel extends \Gini\Controller\CGI {

    public function actionDelete($id) {
        $link = a('link', $id);

        $view = V('admin/channel/delete', [
            'link' => $link
        ]);
        
        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', $view);
    }

}
