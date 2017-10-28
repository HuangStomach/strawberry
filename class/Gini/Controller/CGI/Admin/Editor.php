<?php

namespace Gini\Controller\CGI\Admin;

class Editor extends \Gini\Controller\CGI {

    function actionGet($uniqid, $name, $ext) {
        $filename = "{$name}.{$ext}";
        $fullpath = APP_PATH . '/' . DATA_DIR . "/attached/{$uniqid}/{$filename}";
        $mime_type = \Gini\File::mimeType($fullpath);
        header("Content-Type: $mime_type");
        header('Accept-Ranges: bytes');
        header('Accept-Length:'.filesize($fullpath));
        header("Content-Disposition: attachment; filename=\"$filename\"");
        ob_clean();
        echo file_get_contents($fullpath);
        exit;
    }

}