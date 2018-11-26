<?php

namespace Gini\ORM;

class User extends \Gini\Module\Object
{
    public $name            = 'string:50';
    public $username        = 'string:50';
    public $ref             = 'string:10';
    public $email           = 'string:120';
    public $phone           = 'string:120';
    public $ctime           = 'datetime';

    protected static $db_index = [
        'name', 'ref',
        'email', 'phone'
    ];

    public function save() {
        if ($this->ctime == '0000-00-00 00:00:00' || !isset($this->ctime)) $this->ctime = date('Y-m-d H:i:s');
        return parent::save();
    }

    public function delete() {
        if (in_array($this->username, \Gini\Config::get('site.administrators'))) {
            return false;
        }
        return parent::delete();
    }
    
    public function links () {
        $links = [];

        if (!in_array($this->username, \Gini\Config::get('site.administrators'))) {
            $links['delete'] = [
                'title' => T('删除'),
                'class' => 'btn btn-sm btn-link p-0',
                'url' => "gini-ajax:ajax/strawberry/user/delete/{$this->id}",
            ];
        }

        $links['edit'] = [
            'title' => T('编辑'),
            'class' => 'btn btn-sm btn-link p-0',
            'url' => "strawberry/user/edit/{$this->id}",
        ];
        
        return \Gini\Module\Widget::factory('links', ['items' => $links]);
    }

}
