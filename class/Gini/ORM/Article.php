<?php

namespace Gini\ORM;

class Article extends \Gini\Module\Object
{
    public $title           = 'string:100';
    public $content         = 'string:*';
    public $uniqid          = 'string:100';
    public $type            = 'object:article/type';
    public $active          = 'bool';
    public $author          = 'object:user';
    public $date            = 'datetime';
    public $ctime           = 'datetime';

    protected static $db_index = [
        'title', 'type', 
        'active', 'date'
    ];

    public function save() {
        if ($this->ctime == '0000-00-00 00:00:00' || !isset($this->ctime)) $this->ctime = date('Y-m-d H:i:s');
        return parent::save();
    }

    public function date() {
        return date('Y-m-d', strtotime($this->date));
    }

    public function title($keyword = '') {
        if (!$keyword) return H($this->title);
        $title = H($this->title);
        return str_replace($keyword, "<span class=\"text-nankai font-weight-bold\">{$keyword}</span>", $title);
    }

    public function content() {
        return mb_substr(str_replace('&nbsp;', '', strip_tags($this->content)), 0, 100);
    }

    public function image() {
        $path = APP_PATH . '/' . DATA_DIR . "/attached/{$this->uniqid}/";
        if (!is_dir($path)) return false;
        $files = scandir($path, 1);
        foreach ($files as $file) {
            list($name, $ext) = explode('.', $file);
            if (in_array($ext, ['jpg', 'jpeg', 'gif', 'png', 'bmp'])) {
                return URL("editor/get/{$this->uniqid}/{$name}/{$ext}");
            }
        }
        return false;
    }
    
    public function links () {
        $links = [];
        
        $links['delete'] = [
            'title' => T('删除'),
            'class' => 'btn btn-sm btn-link p-0',
            'url' => "gini-ajax:ajax/strawberry/article/delete/{$this->id}",
        ];
        $links['edit'] = [
            'title' => T('编辑'),
            'class' => 'btn btn-sm btn-link p-0',
            'url' => "strawberry/article/edit/{$this->id}",
        ];

        return \Gini\Module\Widget::factory('links', ['items' => $links]);
    }

}
