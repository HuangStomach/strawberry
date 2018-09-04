<?php

namespace Gini\Controller\CGI\Admin;

class User extends \Gini\Controller\CGI\Layout\Dashboard {

    protected $item;

    function __preAction($action, &$params) {
        $this->item = \Gini\Config::get('sidebar.items')['user'];
        parent::__preAction($action, $params);
    }

    function __index($start = 1, $step = 20) {
        $users = those('user');

        $form = $this->form('get');

        if ($form['keyword']) {
            $keyword = $form['keyword'];
            $users->whose('name')->contains($keyword)
            ->orWhose('ref')->contains($keyword)
            ->orWhose('email')->contains($keyword)
            ->orWhose('phone')->contains($keyword);
        }
        
        $users->limit(($start - 1) * $step, $step);
        
        $pagination = \Gini\Module\Widget::factory('pagination', [
            'uri' => 'admin/user',
            'total' => $users->totalCount(),
            'start' => $start,
            'step' => $step,
            'form' => $form
        ]);
        
        $this->view->body = V('admin/user/list', [
            'item' => $this->item,
            'form' => $form,
            'users' => $users,
            'pagination' => $pagination,
        ]);
    }

    function actionAdd() {
        $form = $this->form('post');

        if ($form) {     
            $validator = new \Gini\CGI\Validator;
            try {
                $validator
                ->validate('name', !!$form['name'], T('姓名不能为空!'))
                ->validate('name', strlen($form['name']) <= 20, T('姓名过长, 最多不能超过20位!'))
                ->validate('username', !!$form['username'], T('用户名不能为空!'))
                ->validate('username', strlen($form['username']) <= 20, T('用户名过长, 最多不能超过20位!'))
                ->validate('password', !!$form['password'], T('密码不能为空!'))
                ->validate('password', strlen($form['password']) <= 18 
                & strlen($form['password']) >= 6, T('密码应保持在6至18位之间!'))
                ->validate('confirm', !!$form['confirm'] && $form['password'] === $form['confirm'], T('两次输入密码不一致!'))
                ->validate('ref', strlen($form['ref']) <= 10, T('学工号过长, 最多不能超过10位!'))
                ->validate('email', strlen($form['email']) <= 50, T('邮箱过长, 最多不能超过50位!'))
                ->validate('phone', strlen($form['phone']) <= 50, T('电话号码过长, 最多不能超过50位!'));
            
                $auth = new \Gini\Auth($form['username']);
                $validator->validate('username', $auth->create($form['password']), T('用户名已存在!'));
                $validator->done();

                $user = a('user');
                $user->username = \Gini\Auth::makeUserName($form['username'], 'database');
                $user->name = $form['name'];
                $user->ref = $form['ref'];
                $user->email = $form['email'];
                $user->phone = $form['phone'];
                if ($user->save()) {
                    $_SESSION['alert'] = [
                        'type' => 'success',
                        'message' => T('用户创建成功'),
                    ];
                }
                else {
                    $_SESSION['alert'] = [
                        'type' => 'danger',
                        'message' => T('用户创建失败'),
                    ];
                    $auth->remove();
                }
                $this->redirect('admin/user');
            }
            catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }
        
        unset($form['password']);
        unset($form['confirm']);
        $this->view->body = V('admin/user/edit', [
            'item' => $this->item,
            'form' => $form
        ]);
    }

    function actionEdit($id) {
        $me = _G('ME');
        $form = $this->form('post');
        $isAdmin = in_array($me->username, \Gini\Config::get('site.administrators'));

        $user = a('user', $id);
        if (!$user->id) $this->redirect('error/404');

        if ($form) {     
            $validator = new \Gini\CGI\Validator;
            try {
                $validator
                ->validate('name', !!$form['name'], T('姓名不能为空!'))
                ->validate('name', strlen($form['name']) <= 20, T('姓名过长, 最多不能超过20位!'))
                ->validate('ref', strlen($form['ref']) <= 10, T('学工号过长, 最多不能超过10位!'))
                ->validate('email', strlen($form['email']) <= 50, T('邮箱过长, 最多不能超过50位!'))
                ->validate('phone', strlen($form['phone']) <= 50, T('电话号码过长, 最多不能超过50位!'));

                if ($isAdmin && $form['password']) {
                    $validator
                    ->validate('password', strlen($form['password']) <= 18 
                    & strlen($form['password']) >= 6, T('密码应保持在6至18位之间!'))
                    ->validate('confirm', !!$form['confirm'] && $form['password'] === $form['confirm'], T('两次输入密码不一致!'));
                }
                $validator->done();

                $user->name = $form['name'];
                $user->ref = $form['ref'];
                $user->email = $form['email'];
                $user->phone = $form['phone'];
                if ($user->save()) {
                    if ($isAdmin && $form['password']) {
                        $auth = new \Gini\Auth($user->username);
                        $auth->changePassword($form['password']);
                    }
                    $_SESSION['alert'] = [
                        'type' => 'success',
                        'message' => T('用户修改成功'),
                    ];
                }
                else {
                    $_SESSION['alert'] = [
                        'type' => 'danger',
                        'message' => T('用户修改失败'),
                    ];
                }
                $this->redirect('admin/user');
            }
            catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }
        
        unset($form['password']);
        unset($form['confirm']);
        $this->view->body = V('admin/user/edit', [
            'edit' => true,
            'item' => $this->item,
            'form' => $form,
            'user' => $user,
            'isAdmin' => $isAdmin,
        ]);
    }

    function actionDelete() {
        $form = $this->form('post');

        if ($form) {
            $user = a('user', $form['id']);
            $auth = new \Gini\Auth($user->username);
            if ($user->id && !in_array($user->username, \Gini\Config::get('site.administrators')) 
            && $user->delete()) {
                $auth->remove();
                $_SESSION['alert'] = [
                    'type' => 'success',
                    'message' => T('用户删除成功'),
                ];
            }
            else {
                $_SESSION['alert'] = [
                    'type' => 'danger',
                    'message' => T('用户删除失败'),
                ];
            }
        }

        $this->redirect('admin/user');
    }

}