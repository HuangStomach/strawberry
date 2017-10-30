<?php

namespace Gini\Controller\CGI\AJAX\Admin;

class Carousel extends \Gini\Controller\CGI {

    public function actionDelete($id) {
        $carousel = a('carousel', $id);

        $view = V('carousel/delete', [
            'carousel' => $carousel
        ]);
        
        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', $view);
    }

}
