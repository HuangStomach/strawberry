<?php

namespace Gini\Controller\CGI\Strawberry;

class File extends \Gini\Controller\CGI\Layout\Dashboard {
    
    protected $item;
        
    function __preAction($action, &$params) {
        $this->item = \Gini\Config::get('sidebar.items')['file'];
        parent::__preAction($action, $params);
    }

    function __index($start = 1, $step = 20) {
        $files = those('file');

        $form = $this->form('get');
        
        if ($form['keyword']) {
            $keyword = $form['keyword'];
            $files->whose('title')->contains($keyword);
        }
        
        $files->limit(($start - 1) * $step, $step);
        
        $pagination = \Gini\Module\Widget::factory('pagination', [
            'uri' => 'strawberry/file',
            'total' => $files->totalCount(),
            'start' => $start,
            'step' => $step,
            'form' => $form
        ]);

        $this->view->body = V('admin/file/list', [
            'item' => $this->item,
            'form' => $form,
            'files' => $files,
            'pagination' => $pagination
        ]);
    }

    function actionAdd() {
        $me = _G('ME');
        $files = $this->form('files')['file'];
        $uniqid = $this->form('post')['uniqid'];
        
        if ($files) {
            $count = count($files['name']);
            for ($i = 0; $i < $count; $i++) {
                $uniqid = uniqid();
                $path = DATA_DIR . "/attached/{$uniqid}";
                \Gini\File::ensureDir($path);

                $ext = \Gini\File::extension($files['name'][$i]);
                $title = explode('.', $files['name'][$i])[0];
                $name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $ext;
                
                if (is_dir($path) && is_uploaded_file($files['tmp_name'][$i])
                && move_uploaded_file($files['tmp_name'][$i], "{$path}/{$name}")) {
                    $file = a('file');
                    $file->title = $title;
                    $file->dir = $path;
                    $file->path = "{$path}/{$name}";
                    $file->extension = $ext;
                    $file->mime = $files['type'][$i];
                    $file->author = $me;
                    
                    if (!$file->save()) {
                        \Gini\File::removeDir($path);
                    }
                }
            }
            $_SESSION['alert'] = [
                'type' => 'success',
                'message' => T('文件上传成功'),
            ];
            $this->redirect('strawberry/file');
        }

        $this->view->body = V('admin/file/edit', [
            'item' => $this->item,
            'form' => $form,
        ]);
    }
    
    function actionDelete() {
        $form = $this->form('post');

        if ($form) {
            $file = a('file', $form['id']);
            $path = APP_PATH . '/' . $file->dir;
            if ($file->id && $file->delete()) {
                if ($file->dir) \Gini\File::removeDir($path);
                $_SESSION['alert'] = [
                    'type' => 'success',
                    'message' => T('文件删除成功'),
                ];
            }
            else {
                $_SESSION['alert'] = [
                    'type' => 'danger',
                    'message' => T('文件删除失败'),
                ];
            }
        }

        $this->redirect('strawberry/file');
    }

}