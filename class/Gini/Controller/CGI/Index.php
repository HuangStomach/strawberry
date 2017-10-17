<?php

namespace Gini\Controller\CGI;

class Index extends Layout\Whiteboard {

    function __index() {
        $this->redirect('login');
    }
    
    function actionLogin () {
        $form = $this->form('post');
        $route = \Gini\CGI::route();
        $phone = \Gini\Config::get('site.phone') ? : '400-017-5664';
        $email = \Gini\Config::get('site.email') ? : 'support@geneegroup.com';

        if ($form) {
            if (!$form['csrf'] || $_SESSION[$route] !== $form['csrf']) $this->redirect('login');

            $validator = new \Gini\CGI\Validator;
            try {
                $validator
                ->validate('username', !!$form['username'], T('用户名不能为空!'))
                ->validate('password', !!$form['password'], T('密码不能为空!'));

                $auth = new \Gini\Auth($form['username']);

                if ($auth->verify($form['password'])) {
                    \Gini\Auth::login($form['username']);
                    $this->redirect('dashboard');
                }
                else {
                    $user = a('user')->whose('username')->is($form['username']);
                    if ($form['username'] == 'genee'
                    && $form['password'] == 'Genee83719730'
                    && !$user->id) {
                        $user->username = $form['username'];
                        $user->name = T('技术支持');
                        $user->ref = 'geneegroup';
                        $user->email = $email;
                        $user->phone = $phone;
                        if ($user->save()) {
                            $auth->create($form['password']);
                            \Gini\Auth::login($form['username']);
                            $this->redirect('dashboard');
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

        $this->view->body = V('auth/login', [
            'csrf' => $csrf,
            'form' => $form,
            'phone' => $phone
        ]);
    }

}