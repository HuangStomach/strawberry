<?php

namespace Gini\Controller\CGI\Admin;

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
            'uri' => 'admin/file',
            'total' => $files->totalCount(),
            'start' => $start,
            'step' => $step,
            'form' => $form
        ]);

        $this->view->body = V('file/list', [
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
                $path = DATA_DIR . "/attached/{$uniqid}";
                \Gini\File::ensureDir($path);

                $ext = \Gini\File::extension($file['name'][$i]);
                $title = pathinfo($file['name'][$i], PATHINFO_FILENAME);
                $name = date("YmdHis") . '_' . rand(10000, 99999) . $ext;
                
                if (is_dir($path) && is_uploaded_file($files['tmp_name'][$i])
                && move_uploaded_file($files['tmp_name'][$i], "{$path}/{$name}")) {
                    $file = a('file');
                    $file->title = $title;
                    $file->path = "{$path}/{$name}";
                    $file->extension = $ext;
                    $file->mime = $files['type'][$i];
                    $file->author = $me;
                    
                    if (!$file->save()) {
                        error_log("{$path}/{$name}");
                        \Gini\File::delete("{$path}/{$name}", true);
                    }
                }
            }
            $_SESSION['alert'] = [
                'type' => 'success',
                'message' => T('文件上传成功'),
            ];
            $this->redirect('admin/file');
        }

        $this->view->body = V('file/edit', [
            'item' => $this->item,
            'uniqid' => uniqid(),
            'form' => $form,
        ]);
    }
    
    function actionDelete() {
        $form = $this->form('post');

        if ($form) {
            $article = a('article', $form['id']);
            $uniqid = $article->uniqid;
            $path = APP_PATH . '/' . DATA_DIR . "/attached/{$uniqid}";
            if ($article->id && $article->delete()) {
                \Gini\File::removeDir($path);
                $_SESSION['alert'] = [
                    'type' => 'success',
                    'message' => T('文章删除成功'),
                ];
            }
            else {
                $_SESSION['alert'] = [
                    'type' => 'danger',
                    'message' => T('文章删除失败'),
                ];
            }
        }

        $this->redirect('admin/article');
    }

}