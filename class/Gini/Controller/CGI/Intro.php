<?php

namespace Gini\Controller\CGI;

class Intro extends \Gini\Controller\CGI\Layout\Home {

    function __index() {
        $intro = a('intro')->whose('key')->is(1);

        $this->view->active = 'intro';
        $this->view->body = V('home/intro', [
            'intro' => $intro
        ]);
    }
    
    function actionPreview($id) {
        $site = a('intro', $id);
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