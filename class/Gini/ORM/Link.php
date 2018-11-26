<?php

namespace Gini\ORM;

class Link extends \Gini\Module\Object
{
    public $name            = 'string:100';
    public $url             = 'string:500';
    public $author          = 'object:user';
    public $type            = 'int,default:0';
    public $dir             = 'string:500'; 
    public $path            = 'string:500'; // 文件全路径
    public $mime            = 'string:100';
    public $ctime           = 'datetime';

    protected static $db_index = [
        'name', 'type'
    ];
    
    const TYPE_FRIENDLY = 0;
    const TYPE_CHANNEL = 1;

    public static $TYPE = [
        self::TYPE_FRIENDLY => '友情链接',
        self::TYPE_CHANNEL => '快速通道',
    ];

    public function save() {
        if ($this->ctime == '0000-00-00 00:00:00' || !isset($this->ctime)) $this->ctime = date('Y-m-d H:i:s');
        return parent::save();
    }
    
    public function links () {
        $links = [];
        $type = 'link';

        switch ($this->type) {
            case self::TYPE_CHANNEL:
                $type = 'channel';
            break;
        }

        $links['edit'] = [
            'title' => T('编辑'),
            'class' => 'btn btn-sm btn-link p-0',
            'url' => "strawberry/{$type}/edit/{$this->id}",
        ];

        $links['delete'] = [
            'title' => T('删除'),
            'class' => 'btn btn-sm btn-link p-0',
            'url' => "gini-ajax:ajax/strawberry/{$type}/delete/{$this->id}",
        ];
        
        return \Gini\Module\Widget::factory('links', ['items' => $links]);
    }

}
