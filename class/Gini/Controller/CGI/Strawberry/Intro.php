<?php

namespace Gini\Controller\CGI\Strawberry;

class Intro extends \Gini\Controller\CGI\Layout\Dashboard {
    
    protected $item;
        
    function __preAction($action, &$params) {
        $this->item = \Gini\Config::get('sidebar.items')['intro'];
        parent::__preAction($action, $params);
    }

    function __index() {
        $me = _G('ME');
        $form = $this->form('post');
        $file = $this->form('files')['file'];
        $intro = a('intro')->whose('key')->is(1);

        if ($form) {
            $intro->key = 1;
            $intro->content = $form['content'];
            $intro->uniqid = $form['uniqid'];
            
            if (!$file['error'] && $file['tmp_name']) {
                $path = DATA_DIR . "/attached/{$form['uniqid']}";
                \Gini\File::ensureDir($path);
    
                $ext = \Gini\File::extension($file['name']);
                $name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $ext;
                
                if (is_dir($path) && is_uploaded_file($file['tmp_name'])
                && move_uploaded_file($file['tmp_name'], "{$path}/{$name}")) {
                    $intro->dir = $path;
                    $intro->path = "{$path}/{$name}";
                    $intro->mime = $file['type'];
                }
                else {
                    $_SESSION['alert'] = [
                        'type' => 'danger',
                        'message' => T('简介更新失败'),
                    ];
                    $this->redirect('strawberry/intro');
                }
            }
            elseif (!$form['exists']) {
                $path = APP_PATH . '/' . $intro->dir;
                \Gini\File::removeDir($path);
                $intro->dir = '';
                $intro->path =  '';
                $intro->mime = $file['type'];
            }

            if ($intro->save()) {
                $_SESSION['alert'] = [
                    'type' => 'success',
                    'message' => T('简介更新成功'),
                ];
            }
            else {
                $_SESSION['alert'] = [
                    'type' => 'danger',
                    'message' => T('简介更新失败'),
                ];
            }
            $this->redirect('strawberry/intro');
        }
        
        $this->view->body = V('admin/intro/edit', [
            'form' => $form,
            'intro' => $intro,
            'item' => $this->item,
            'uniqid' => $intro->uniqid ? : uniqid()
        ]);
    }

}