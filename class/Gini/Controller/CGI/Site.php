<?php

namespace Gini\Controller\CGI;

class Site extends \Gini\Controller\CGI {
    
    function actionPreview($id) {
        $site = a('site', $id);
        if (!$site->id) $this->redirect('error/404');
        $fullpath = APP_PATH . '/' . $site->path;
        
        header("Content-Type: {$site->mime}");
        header('Accept-Ranges: bytes');
        header('Accept-Length:' . filesize($fullpath));
        header("Content-Disposition: attachment; filename=\"{$site->name}\"");
        ob_clean();
        echo file_get_contents($fullpath);
        exit;
    }

}