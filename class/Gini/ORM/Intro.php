<?php

namespace Gini\ORM;

class Intro extends \Gini\Module\Object
{
    public $content     = 'string:*';
    public $key         = 'int';
    public $uniqid      = 'string:100';
    public $dir         = 'string:500';
    public $path        = 'string:500'; // 文件全路径
    public $mime        = 'string:100';
    public $ctime       = 'datetime';

    protected static $db_index = [
        'unique:key'
    ];
}
