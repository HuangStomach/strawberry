<?php

namespace Gini\Controller\CGI;

class Link extends \Gini\Controller\CGI {
    
    function actionPreview($id) {
        $link = a('link', $id);
        if (!$link->id) $this->redirect('error/404');
        $fullpath = APP_PATH . '/' . $link->path;
        
        header("Content-Type: {$link->mime}");
        header('Accept-Ranges: bytes');
        header('Accept-Length:' . filesize($fullpath));
        header("Content-Disposition: attachment; filename=\"{$link->name}\"");
        ob_clean();
        echo file_get_contents($fullpath);
        exit;
    }

}
