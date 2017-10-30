<?php

namespace Gini\Controller\CGI\Admin;

class Carousel extends \Gini\Controller\CGI\Layout\Dashboard {
    
    protected $item;
        
    function __preAction($action, &$params) {
        $this->item = \Gini\Config::get('sidebar.items')['carousel'];
        parent::__preAction($action, $params);
    }

    function __index() {
        $carousel = those('carousel')->orderBy('index');
        $form = $this->form('get');

        $this->view->body = V('carousel/list', [
            'item' => $this->item,
            'carousel' => $carousel
        ]);
    }

    function actionAdd() {
        $me = _G('ME');
        $file = $this->form('files')['file'];
        $form = $this->form('post');
        
        if ($form && $file) {
            $validator = new \Gini\CGI\Validator;
            try {
                $ext = \Gini\File::extension($file['name']);

                $validator
                ->validate('title', !!$form['title'], T('请输入标题!'))
                ->validate('title', strlen($form['title']) <= 50, T('标题过长, 最多不能超过50位!'))
                ->validate('file', in_array($ext, ['jpg', 'jpeg', 'gif', 'png', 'bmp']), T('请使用常用图片格式!'));
                $validator->done();

                $uniqid = uniqid();
                $path = DATA_DIR . "/attached/{$uniqid}";
                \Gini\File::ensureDir($path);
    
                $name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $ext;
                
                if (is_dir($path) && is_uploaded_file($file['tmp_name'])
                && move_uploaded_file($file['tmp_name'], "{$path}/{$name}")) {
                    $carousel = a('carousel');
                    $carousel->title = $form['title'];
                    $carousel->name = $name;
                    $carousel->index = (int)$form['index'];
                    $carousel->dir = $path;
                    $carousel->path = "{$path}/{$name}";
                    $carousel->mime = $file['type'];
                    $carousel->author = $me;
                    
                    if (!$carousel->save()) {
                        \Gini\File::removeDir($path);
                        $_SESSION['alert'] = [
                            'type' => 'danger',
                            'message' => T('文件上传失败'),
                        ];
                    }
                    else {
                        $_SESSION['alert'] = [
                            'type' => 'success',
                            'message' => T('文件上传成功'),
                        ];
                    }
                }

                $this->redirect('admin/carousel');
            }
            catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }

        $this->view->body = V('carousel/edit', [
            'item' => $this->item,
            'form' => $form,
        ]);
    }

    function actionEdit($id) {
        $me = _G('ME');
        
        $carousel = a('carousel', $id);
        if (!$carousel->id) $this->redirect('error/404');

        $file = $this->form('files')['file'];
        $form = $this->form('post');
        
        if ($form) {
            $validator = new \Gini\CGI\Validator;
            try {
                $validator
                ->validate('title', !!$form['title'], T('请输入标题!'))
                ->validate('title', strlen($form['title']) <= 50, T('标题过长, 最多不能超过50位!'));
                if (!$file['error']) {    
                    $ext = \Gini\File::extension($file['name']);
                    $validator
                    ->validate('file', in_array($ext, ['jpg', 'jpeg', 'gif', 'png', 'bmp']), T('请使用常用图片格式!'));
                }
                $validator->done();

                $carousel->title = $form['title'];
                $carousel->index = (int)$form['index'];

                if (!$file['error']) {  
                    $uniqid = uniqid();
                    $path = DATA_DIR . "/attached/{$uniqid}";
                    \Gini\File::ensureDir($path);
        
                    $name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $ext;
                    
                    if (is_dir($path) && is_uploaded_file($file['tmp_name'])
                    && move_uploaded_file($file['tmp_name'], "{$path}/{$name}")) {
                        $carousel->name = $name;
                        $carousel->dir = $path;
                        $carousel->path = "{$path}/{$name}";
                        $carousel->mime = $file['type'];
                    }
                    else {
                        $_SESSION['alert'] = [
                            'type' => 'danger',
                            'message' => T('图片编辑失败'),
                        ];
                        $this->redirect('admin/carousel');
                    }
                }
                    
                if (!$carousel->save()) {
                    \Gini\File::removeDir($path);
                    $_SESSION['alert'] = [
                        'type' => 'danger',
                        'message' => T('图片编辑失败'),
                    ];
                }
                else {
                    $_SESSION['alert'] = [
                        'type' => 'success',
                        'message' => T('图片编辑成功'),
                    ];
                }

                $this->redirect('admin/carousel');
            }
            catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }
        
        $this->view->body = V('carousel/edit', [
            'item' => $this->item,
            'form' => $form,
            'carousel' => $carousel
        ]);
    }
    
    function actionDelete() {
        $form = $this->form('post');

        if ($form) {
            $carousel = a('carousel', $form['id']);
            $path = APP_PATH . '/' . $carousel->dir;
            if ($carousel->id && $carousel->delete()) {
                \Gini\File::removeDir($path);
                $_SESSION['alert'] = [
                    'type' => 'success',
                    'message' => T('图片删除成功'),
                ];
            }
            else {
                $_SESSION['alert'] = [
                    'type' => 'danger',
                    'message' => T('图片删除失败'),
                ];
            }
        }

        $this->redirect('admin/carousel');
    }

}