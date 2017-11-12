<?php

namespace Gini\Controller\CGI\AJAX\Index;

class Equipment extends \Gini\Controller\CGI {

    public function actionSwiper() {
        $equipments = those('equipment')->whose('show')->is(true);

        $view = V('home/index/equipment', [
            'equipments' => $equipments
        ]);

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', $view);
    }

}