<?php

namespace Gini\Controller\CGI\Strawberry;

class Channel extends \Gini\Controller\CGI\Layout\Dashboard {
    
    protected $item;
        
    function __preAction($action, &$params) {
        $this->item = \Gini\Config::get('sidebar.items')['channel'];
        parent::__preAction($action, $params);
    }

    function __index($start = 1, $step = 20) {
        $links = those('link')->whose('type')->is(\Gini\ORM\Link::TYPE_CHANNEL);;
        $form = $this->form('get');
        
        if ($form['keyword']) {
            $keyword = $form['keyword'];
            $links->whose('name')->contains($keyword);
        }

        $links->limit(($start - 1) * $step, $step);
        
        $pagination = \Gini\Module\Widget::factory('pagination', [
            'uri' => 'strawberry/channel',
            'total' => $links->totalCount(),
            'start' => $start,
            'step' => $step,
            'form' => $form
        ]);

        $this->view->body = V('admin/channel/list', [
            'item' => $this->item,
            'form' => $form,
            'links' => $links,
            'pagination' => $pagination
        ]);
    }

    function actionAdd() {
        $me = _G('ME');
        $form = $this->form('post');
        $file = $this->form('files')['file'];

        if ($form) {
            $validator = new \Gini\CGI\Validator;
            try {
                $validator
                ->validate('name', !!$form['name'], T('请输入名称!'))
                ->validate('name', strlen($form['name']) <= 50, T('名称过长, 最多不能超过50位!'))
                ->validate('url', !!$form['url'], T('请输入地址!'));
                $validator->done();

                $link = a('link');
                $link->name = $form['name'];
                $link->url = $form['url'];
                $link->author = $me;
                $link->type = \Gini\ORM\Link::TYPE_CHANNEL;

                if ($file) {
                    $uniqid = uniqid();
                    $path = DATA_DIR . "/attached/{$uniqid}";
                    \Gini\File::ensureDir($path);
        
                    $ext = \Gini\File::extension($file['name']);
                    $name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $ext;
                    
                    if (is_dir($path) && is_uploaded_file($file['tmp_name'])
                    && move_uploaded_file($file['tmp_name'], "{$path}/{$name}")) {
                        $link->dir = $path;
                        $link->path = "{$path}/{$name}";
                        $link->mime = $file['type'];
                    }
                }

                if ($link->save()) {
                    $_SESSION['alert'] = [
                        'type' => 'success',
                        'message' => T('通道创建成功'),
                    ];
                }
                else {
                    $_SESSION['alert'] = [
                        'type' => 'danger',
                        'message' => T('通道创建失败'),
                    ];
                }
                $this->reditect('strawberry/channel');
            }
            catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }

        $this->view->body = V('admin/channel/edit', [
            'item' => $this->item,
            'form' => $form,
        ]);
    }

    function actionEdit($id) {
        $me = _G('ME');
        $form = $this->form('post');
        $file = $this->form('files')['file'];

        $link = a('link', $id);
        if (!$link->id) $this->redirect('error/404');
        
        if ($form) {
            $validator = new \Gini\CGI\Validator;
            try {
                $validator
                ->validate('name', !!$form['name'], T('请输入名称!'))
                ->validate('name', strlen($form['name']) <= 50, T('名称过长, 最多不能超过50位!'))
                ->validate('url', !!$form['url'], T('请输入地址!'));
                $validator->done();

                $link->name = $form['name'];
                $link->url = $form['url'];
                $link->author = $me;
                
                if (!$file['error'] && $file['tmp_name']) {  
                    $uniqid = uniqid();
                    $path = DATA_DIR . "/attached/{$uniqid}";
                    \Gini\File::ensureDir($path);
        
                    $ext = \Gini\File::extension($file['name']);
                    $name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $ext;
                    
                    if (is_dir($path) && is_uploaded_file($file['tmp_name'])
                    && move_uploaded_file($file['tmp_name'], "{$path}/{$name}")) {
                        $link->dir = $path;
                        $link->path = "{$path}/{$name}";
                        $link->mime = $file['type'];
                    }
                    else {
                        $_SESSION['alert'] = [
                            'type' => 'danger',
                            'message' => T('图片编辑失败'),
                        ];
                        $this->reditect('strawberry/link');
                    }
                }
                elseif (!$form['exists']) {
                    if ($link->dir) {
                        $path = APP_PATH . '/' . $link->dir;
                        \Gini\File::removeDir($path);
                    }
                    $link->dir = '';
                    $link->path =  '';
                    $link->mime = $file['type'];
                }

                if ($link->save()) {
                    $_SESSION['alert'] = [
                        'type' => 'success',
                        'message' => T('通道修改成功'),
                    ];
                }
                else {
                    $_SESSION['alert'] = [
                        'type' => 'danger',
                        'message' => T('通道修改失败'),
                    ];
                }
                $this->reditect('strawberry/channel');
            }
            catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }
        
        $this->view->body = V('admin/channel/edit', [
            'item' => $this->item,
            'form' => $form,
            'link' => $link
        ]);
    }
    
    function actionDelete() {
        $form = $this->form('post');

        if ($form) {
            $link = a('link', $form['id']);
            $path = APP_PATH . '/' . $link->dir;
            if ($link->id && $link->delete()) {
                if ($link->dir) \Gini\File::removeDir($path);
                $_SESSION['alert'] = [
                    'type' => 'success',
                    'message' => T('通道删除成功'),
                ];
            }
            else {
                $_SESSION['alert'] = [
                    'type' => 'danger',
                    'message' => T('通道删除失败'),
                ];
            }
        }

        $this->reditect('strawberry/channel');
    }

}