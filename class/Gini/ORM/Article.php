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
    
    public function links () {
        $links = [];
        
        $links['delete'] = [
            'title' => T('删除'),
            'class' => 'btn btn-sm btn-link p-0',
            'url' => "gini-ajax:ajax/admin/article/delete/{$this->id}",
        ];
        $links['edit'] = [
            'title' => T('编辑'),
            'class' => 'btn btn-sm btn-link p-0',
            'url' => "admin/article/edit/{$this->id}",
        ];

        return \Gini\Module\Widget::factory('links', ['items' => $links]);
    }

}
