<?php

namespace Gini\ORM;

class Carousel extends \Gini\Module\Object
{
    public $title   = 'string:100';
    public $name    = 'string:100'; // 轮播图文件名
    public $index   = 'int';
    public $dir     = 'string:500'; 
    public $path    = 'string:500'; // 文件全路径
    public $mime    = 'string:100';
    public $author  = 'object:user';
    public $ctime   = 'datetime';

    protected static $db_index = [
        'index'
    ];

    public function save() {
        if ($this->ctime == '0000-00-00 00:00:00' || !isset($this->ctime)) $this->ctime = date('Y-m-d H:i:s');
        return parent::save();
    }
    
    public function links () {
        $links = [];
        
        $links['edit'] = [
            'title' => T('编辑'),
            'class' => 'btn btn-sm btn-warning',
            'url' => "admin/carousel/edit/{$this->id}",
        ];
        
        $links['delete'] = [
            'title' => T('删除'),
            'class' => 'btn btn-sm btn-danger',
            'url' => "gini-ajax:ajax/admin/carousel/delete/{$this->id}",
        ];

        return \Gini\Module\Widget::factory('links', ['items' => $links]);
    }

}
