<?php

namespace Gini\ORM\Article;

class Type extends \Gini\Module\Object
{
    public $name        = 'string:100';
    public $key         = 'string:50';
    public $parent      = 'object:article/type';
    public $ctime       = 'datetime';

    protected static $db_index = [
        'name', 'parent'
    ];

    public function save() {
        if ($this->ctime == '0000-00-00 00:00:00' || !isset($this->ctime)) $this->ctime = date('Y-m-d H:i:s');
        return parent::save();
    }

}
