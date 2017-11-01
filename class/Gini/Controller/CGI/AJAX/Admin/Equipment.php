<?php

namespace Gini\Controller\CGI\AJAX\Admin;

class Equipment extends \Gini\Controller\CGI {

    public function actionSwitch($id) {
        $equipment = a('equipment', $id);
        if (!$equipment->id) return false;

        $equipment->show = !$equipment->show;
        $equipment->save();
        
        return \Gini\IoC::construct('\Gini\CGI\Response\Redirect');
    }

}
