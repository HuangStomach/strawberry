<?php

namespace Gini\Controller\CGI\Strawberry;

class Site extends \Gini\Controller\CGI\Layout\Dashboard {
    
    protected $item;
        
    function __preAction($action, &$params) {
        $this->item = \Gini\Config::get('sidebar.items')['site'];
        parent::__preAction($action, $params);
    }

    function __index($start = 1, $step = 20) {
        $sites = those('site');
        $form = $this->form('get');
        
        if ($form['keyword']) {
            $keyword = $form['keyword'];
            $sites->whose('name')->contains($keyword);
        }

        $sites->limit(($start - 1) * $step, $step);
        
        $pagination = \Gini\Module\Widget::factory('pagination', [
            'uri' => 'strawberry/site',
            'total' => $sites->totalCount(),
            'start' => $start,
            'step' => $step,
            'form' => $form
        ]);

        $this->view->body = V('admin/site/list', [
            'item' => $this->item,
            'form' => $form,
            'sites' => $sites,
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
                ->validate('name', strlen($form['name']) <= 200, T('名称过长, 最多不能超过50位!'))
                ->validate('url', !!$form['url'], T('请输入地址!'));
                $validator->done();

                $site = a('site');
                $site->name = $form['name'];
                $site->url = $form['url'];
                $site->error = false;
                $site->show = $form['show'] == 'on';
                $site->sync = $form['sync'] == 'on';
                $site->author = $me;

                if ($file) {
                    $uniqid = uniqid();
                    $path = DATA_DIR . "/attached/{$uniqid}";
                    \Gini\File::ensureDir($path);
        
                    $ext = \Gini\File::extension($file['name']);
                    $name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $ext;
                    
                    if (is_dir($path) && is_uploaded_file($file['tmp_name'])
                    && move_uploaded_file($file['tmp_name'], "{$path}/{$name}")) {
                        $site->dir = $path;
                        $site->path = "{$path}/{$name}";
                        $site->mime = $file['type'];
                    }
                }

                if ($site->save()) {
                    $_SESSION['alert'] = [
                        'type' => 'success',
                        'message' => T('链接创建成功'),
                    ];
                }
                else {
                    $_SESSION['alert'] = [
                        'type' => 'danger',
                        'message' => T('链接创建失败'),
                    ];
                }
                $this->reditect('strawberry/site');
            }
            catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }

        // check比较奇怪单独拿出来处理
        $shown = $form['show'] ? ($form['show'] == 'on' ? 'checked' : '') : 'checked';
        $synced = $form['sync'] ? ($form['sync'] == 'on' ? 'checked' : '') : 'checked';
        $this->view->body = V('admin/site/edit', [
            'synced' => $synced,
            'shown' => $shown,
            'item' => $this->item,
            'form' => $form,
        ]);
    }

    function actionEdit($id) {
        $me = _G('ME');
        $form = $this->form('post');
        $file = $this->form('files')['file'];

        $site = a('site', $id);
        if (!$site->id) $this->redirect('error/404');
        
        if ($form) {
            $validator = new \Gini\CGI\Validator;

            try {
                $validator
                ->validate('name', !!$form['name'], T('请输入名称!'))
                ->validate('name', strlen($form['name']) <= 200, T('名称过长, 最多不能超过50位!'))
                ->validate('url', !!$form['url'], T('请输入地址!'));
                $validator->done();

                $site->name = $form['name'];
                $site->url = $form['url'];
                $site->show = $form['show'] == 'on';
                $site->sync = $form['sync'] == 'on';
                $site->author = $me;
                
                if (!$file['error'] && $file['tmp_name']) {  
                    $uniqid = uniqid();
                    $path = DATA_DIR . "/attached/{$uniqid}";
                    \Gini\File::ensureDir($path);
        
                    $ext = \Gini\File::extension($file['name']);
                    $name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $ext;
                    
                    if (is_dir($path) && is_uploaded_file($file['tmp_name'])
                    && move_uploaded_file($file['tmp_name'], "{$path}/{$name}")) {
                        $site->dir = $path;
                        $site->path = "{$path}/{$name}";
                        $site->mime = $file['type'];
                    }
                    else {
                        $_SESSION['alert'] = [
                            'type' => 'danger',
                            'message' => T('图片编辑失败'),
                        ];
                        $this->reditect('strawberry/site');
                    }
                }
                elseif (!$form['exists']) {
                    if ($site->dir) {
                        $path = APP_PATH . '/' . $site->dir;
                        \Gini\File::removeDir($path);
                    }
                    $site->dir = '';
                    $site->path =  '';
                    $site->mime = $file['type'];
                }

                if ($site->save()) {
                    $_SESSION['alert'] = [
                        'type' => 'success',
                        'message' => T('链接修改成功'),
                    ];
                }
                else {
                    $_SESSION['alert'] = [
                        'type' => 'danger',
                        'message' => T('链接修改失败'),
                    ];
                }
                $this->reditect('strawberry/site');
            }
            catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }
        
        // check比较奇怪单独拿出来处理
        $shown = $form['show'] 
        ? ($form['show'] == 'on' ? 'checked' : '') 
        : ($site->show ? 'checked' : '');
        // check比较奇怪单独拿出来处理
        $synced = $form['sync'] 
        ? ($form['sync'] == 'on' ? 'checked' : '') 
        : ($site->sync ? 'checked' : '');
        $this->view->body = V('admin/site/edit', [
            'synced' => $synced,
            'shown' => $shown,
            'item' => $this->item,
            'form' => $form,
            'site' => $site
        ]);
    }
    
    function actionDelete() {
        $form = $this->form('post');

        if ($form) {
            $site = a('site', $form['id']);
            $path = APP_PATH . '/' . $site->dir;
            if ($site->id && $site->delete()) {
                if ($site->dir) \Gini\File::removeDir($path);
                $_SESSION['alert'] = [
                    'type' => 'success',
                    'message' => T('链接删除成功'),
                ];
            }
            else {
                $_SESSION['alert'] = [
                    'type' => 'danger',
                    'message' => T('链接删除失败'),
                ];
            }
        }

        $this->reditect('strawberry/site');
    }

    function actionSync($id) {
        $site = a('site', $id);
        if (!$site->id) $this->redirect('error/404');

        $now = time();
        if ($now - strtotime($site->sync_time) <= 300) {
            $_SESSION['alert'] = [
                'type' => 'warning',
                'message' => T('距上次同步时间间距过小，请5分钟后再试！'),
            ];
        }
        else {
            $site->sync();
            $_SESSION['alert'] = [
                'type' => 'success',
                'message' => T('正在同步，请稍后检查同步状态！'),
            ];
        }

        $this->reditect('strawberry/site');
    }

}