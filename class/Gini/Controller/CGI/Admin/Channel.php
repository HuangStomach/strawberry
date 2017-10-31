<?php

namespace Gini\Controller\CGI\Admin;

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
            'uri' => 'admin/channel',
            'total' => $links->totalCount(),
            'start' => $start,
            'step' => $step,
            'form' => $form
        ]);

        $this->view->body = V('channel/list', [
            'item' => $this->item,
            'form' => $form,
            'links' => $links,
            'pagination' => $pagination
        ]);
    }

    function actionAdd() {
        $me = _G('ME');
        $form = $this->form('post');

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
                $this->redirect('admin/channel');
            }
            catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }

        $this->view->body = V('channel/edit', [
            'item' => $this->item,
            'form' => $form,
        ]);
    }

    function actionEdit($id) {
        $me = _G('ME');
        $form = $this->form('post');

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

                $link = a('link');
                $link->name = $form['name'];
                $link->url = $form['url'];
                $link->author = $me;
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
                $this->redirect('admin/channel');
            }
            catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }
        
        $this->view->body = V('channel/edit', [
            'item' => $this->item,
            'form' => $form,
            'link' => $link
        ]);
    }
    
    function actionDelete() {
        $form = $this->form('post');

        if ($form) {
            $link = a('link', $form['id']);
            if ($link->id && $link->delete()) {
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

        $this->redirect('admin/channel');
    }

}