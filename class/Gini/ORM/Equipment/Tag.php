<?php

namespace Gini\ORM\Equipment;

class Tag extends \Gini\Module\Object
{
    public $name            = 'string:100';

    protected static $db_index = [
        'name'
    ];

}
