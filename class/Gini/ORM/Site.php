<?php

namespace Gini\ORM;

class Site extends \Gini\Module\Object
{
    public $name            = 'string:50';
    public $url             = 'string:250';
    public $sync_time       = 'datetime';
    public $error           = 'bool';
    public $author          = 'object:user';
    public $ctime           = 'datetime';

    protected static $db_index = [
        'name'
    ];

    public function save() {
        if ($this->ctime == '0000-00-00 00:00:00' || !isset($this->ctime)) $this->ctime = date('Y-m-d H:i:s');
        return parent::save();
    }
    
    public function links() {
        $links = [];
        
        $links['delete'] = [
            'title' => T('删除'),
            'class' => 'btn btn-sm btn-link p-0',
            'url' => "gini-ajax:ajax/admin/site/delete/{$this->id}",
        ];

        $links['edit'] = [
            'title' => T('编辑'),
            'class' => 'btn btn-sm btn-link p-0',
            'url' => "admin/site/edit/{$this->id}",
        ];

        $links['sync'] = [
            'title' => T('同步'),
            'class' => 'btn btn-sm btn-link p-0',
            'url' => "admin/site/sync/{$this->id}",
        ];
        
        return \Gini\Module\Widget::factory('links', ['items' => $links]);
    }

    public function sync() {
        return true;
    }

}
