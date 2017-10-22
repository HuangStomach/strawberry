<?php

namespace Gini\Controller\CGI;

class Article extends Layout\Dashboard {
    
    protected $item;
        
    function __preAction($action, &$params) {
        $this->item = \Gini\Config::get('sidebar.items')['article'];
        
        if (!those('article/type')->totalCount()) {
            $types = \Gini\Config::get('article.type');
            foreach ($types as $type) {
                $articleType = a('article/type');
                $articleType->key = $type['key'];
                $articleType->name = $type['name'];
                $articleType->save();
            }
        }

        parent::__preAction($action, $params);
    }

    function __index($start = 1, $step = 20) {
        $articles = those('article');

        $form = $this->form('get');
        if ($form) {
            if ($form['keyword']) {
                $keyword = $form['keyword'];
                $articles->whose('title')->contains($keyword);
            }
        }

        $articles->limit(($start - 1) * $step, $step);
        
        $pagination = \Gini\Module\Widget::factory('pagination', [
            'uri' => 'article',
            'total' => $articles->totalCount(),
            'start' => $start,
            'step' => $step,
            'form' => $form
        ]);

        $this->view->body = V('article/list', [
            'item' => $this->item,
            'form' => $form,
            'articles' => $articles,
            'pagination' => $pagination
        ]);
    }

    function actionAdd() {
        $me = _G('ME');
        $form = $this->form('post');
        $types = those('article/type');

        if ($form) {
            $validator = new \Gini\CGI\Validator;
            try {
                $validator
                ->validate('title', !!$form['title'], T('请输入标题!'));
                $validator->done();

                $article = a('article');
                $article->title = $form['title'];
                $article->content = $form['content'];
                $article->uniqid = $form['uniqid'];
                $article->type = a('article/type', $form['type']);
                $article->active = $form['active'] == 'on';
                $article->author = $me;
                $article->date = $form['date'];
                if ($article->save()) {
                    $_SESSION['alert'] = [
                        'type' => 'success',
                        'message' => T('文章创建成功'),
                    ];
                }
                else {
                    $_SESSION['alert'] = [
                        'type' => 'danger',
                        'message' => T('文章创建失败'),
                    ];
                }
                $this->redirect('article');
            }
            catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }

        // check比较奇怪单独拿出来处理
        $checked = $form['active'] ? ($form['active'] == 'on' ? 'checked' : '') : 'checked';
        $this->view->body = V('article/edit', [
            'item' => $this->item,
            'form' => $form,
            'types' => $types,
            'uniqid' => uniqid(),
            'checked' => $checked
        ]);
    }

    function actionEdit($id) {
        $me = _G('ME');
        $form = $this->form('post');
        $types = those('article/type');

        $article = a('article', $id);
        if (!$article->id) $this->redirect('error/404');
        
        if ($form) {
            $validator = new \Gini\CGI\Validator;
            try {
                $validator
                ->validate('title', !!$form['title'], T('请输入标题!'));
                $validator->done();
                
                $article->title = $form['title'];
                $article->content = $form['content'];
                $article->type = a('article/type', $form['type']);
                $article->active = $form['active'] == 'on';
                $article->date = $form['date'];
                if ($article->save()) {
                    $_SESSION['alert'] = [
                        'type' => 'success',
                        'message' => T('文章创建成功'),
                    ];
                }
                else {
                    $_SESSION['alert'] = [
                        'type' => 'danger',
                        'message' => T('文章创建失败'),
                    ];
                }
                $this->redirect('article');
            }
            catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }

        // check比较奇怪单独拿出来处理
        $checked = $form['active'] 
        ? ($form['active'] == 'on' ? 'checked' : '') 
        : ($article->active ? 'checked' : '');
        $this->view->body = V('article/edit', [
            'item' => $this->item,
            'form' => $form,
            'types' => $types,
            'uniqid' => $article->uniqid,
            'article' => $article,
            'checked' => $checked
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

        $this->redirect('article');
    }

}