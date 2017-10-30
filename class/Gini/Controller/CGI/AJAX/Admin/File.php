<?php

namespace Gini\Controller\CGI\AJAX\Admin;

class File extends \Gini\Controller\CGI {

    public function actionDelete($id) {
        $file = a('file', $id);

        $view = V('file/delete', [
            'file' => $file
        ]);
        
        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', $view);
    }

}
