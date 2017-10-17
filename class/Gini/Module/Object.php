<?php

namespace Gini\Module;

class Object extends \Gini\ORM\Object
{
    public function isAllowedTo($action, $object = null, $when = null, $where = null) {
        $name = $this->name();
        
        if ($object === null) return \Gini\Event::trigger("{$name}.isAllowedTo[$action]", $this, $action, null, $when, $where);

        $oname = is_string($object) ? $object : $object->name();
        return \Gini\Event::trigger([
            "{$name}.isAllowedTo[$action].$oname",
            "{$name}.isAllowedTo[$action].*"
        ], $this, $action, $object, $when, $where);
    }

    public function links () {
        return \Gini\Model\Widget::factory('links', ['items' => []]);
    }
}
