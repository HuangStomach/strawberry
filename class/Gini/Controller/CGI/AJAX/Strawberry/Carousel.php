<?php

namespace Gini\Controller\CGI\AJAX\Strawberry;

class Carousel extends \Gini\Controller\CGI {

    public function actionDelete($id) {
        $carousel = a('carousel', $id);

        $view = V('admin/carousel/delete', [
            'carousel' => $carousel
        ]);
        
        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', $view);
    }

}
