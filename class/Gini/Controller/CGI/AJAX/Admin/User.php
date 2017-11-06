<?php

namespace Gini\Controller\CGI\AJAX\Admin;

class User extends \Gini\Controller\CGI {

    public function actionDelete($id) {
        $user = a('user', $id);

        $view = V('admin/user/delete', [
            'user' => $user
        ]);
        
        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', $view);
    }

}