<?php

namespace Gini\Controller\CGI;

class File extends \Gini\Controller\CGI {

    function actionDownload($id) {
        $file = a('file', $id);
        if (!$file->id) $this->redirect('error/404');
        $fullpath = APP_PATH . '/' . $file->path;
        
        header("Content-Type: {$file->mime}");
        header('Accept-Ranges: bytes');
        header('Accept-Length:' . filesize($fullpath));
        header("Content-Disposition: attachment; filename=\"{$file->title}.{$file->extension}\"");
        ob_clean();
        echo file_get_contents($fullpath);
        exit;
    }

}