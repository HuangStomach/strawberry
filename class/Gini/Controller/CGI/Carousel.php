<?php

namespace Gini\Controller\CGI;

class Carousel extends \Gini\Controller\CGI {
    
    function actionPreview($id) {
        $carousel = a('carousel', $id);
        if (!$carousel->id) $this->redirect('error/404');
        $fullpath = APP_PATH . '/' . $carousel->path;
        
        header("Content-Type: {$carousel->mime}");
        header('Accept-Ranges: bytes');
        header('Accept-Length:' . filesize($fullpath));
        header("Content-Disposition: attachment; filename=\"{$carousel->name}\"");
        ob_clean();
        echo file_get_contents($fullpath);
        exit;
    }

}