<?php

namespace Gini\Controller\CGI;

class File extends \Gini\Controller\CGI\Layout\Home {

    function __index($start = 1, $step = 15) {
        $files = those('file');
        $files->limit(($start - 1) * $step, $step);
        
        $pagination = \Gini\Module\Widget::factory('pagination', [
            'hiddenDes' => true,
            'uri' => "file",
            'total' => $files->totalCount(),
            'start' => $start,
            'step' => $step,
            'align' => 'center'
        ]);

        $this->view->active = 'file';
        $this->view->body = V('home/file/list', [
            'files' => $files,
            'pagination' => $pagination
        ]);
    }

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