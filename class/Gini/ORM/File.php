<?php

namespace Gini\ORM;

class File extends \Gini\Module\Object
{
    public $title           = 'string:100';
    public $path            = 'string:500'; // 文件全路径
    public $extension       = 'string:50';
    public $mime            = 'string:100';
    public $author          = 'object:user';
    public $ctime           = 'datetime';

    protected static $db_index = [
        'title', 'name'
    ];

    public function save() {
        if ($this->ctime == '0000-00-00 00:00:00' || !isset($this->ctime)) $this->ctime = date('Y-m-d H:i:s');
        return parent::save();
    }
    
    public function links () {
        $links = [];
        
        $links['delete'] = [
            'title' => T('删除'),
            'class' => 'btn btn-sm btn-link p-0',
            'url' => "gini-ajax:ajax/admin/file/delete/{$this->id}",
        ];

        return \Gini\Module\Widget::factory('links', ['items' => $links]);
    }

}
