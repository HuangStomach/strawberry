<?php

namespace Gini\ORM;

class Link extends \Gini\Module\Object
{
    public $name            = 'string:100';
    public $url             = 'string:500';
    public $author          = 'object:user';
    public $ctime           = 'datetime';

    protected static $db_index = [
        'name'
    ];

    public function save() {
        if ($this->ctime == '0000-00-00 00:00:00' || !isset($this->ctime)) $this->ctime = date('Y-m-d H:i:s');
        return parent::save();
    }
    
    public function links () {
        $links = [];

        $links['edit'] = [
            'title' => T('编辑'),
            'class' => 'btn btn-sm btn-link p-0',
            'url' => "admin/link/edit/{$this->id}",
        ];

        $links['delete'] = [
            'title' => T('删除'),
            'class' => 'btn btn-sm btn-link p-0',
            'url' => "gini-ajax:ajax/admin/link/delete/{$this->id}",
        ];
        
        return \Gini\Module\Widget::factory('links', ['items' => $links]);
    }

}
