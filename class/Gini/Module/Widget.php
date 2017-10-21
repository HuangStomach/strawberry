<?php

namespace Gini\Module;

class Widget extends \Gini\View {

    function __construct($name, $vars=NULL){
        $basename = basename($name);

        $name = ($basename != $name) ? $name : 'widgets/'.$name;
        parent::__construct($name, $vars);
    }

    static function factory($name, $vars=NULL) {

        $basename = basename($name);
        if($basename == $name) {
            $class_name = '\Gini\Module\Widgets\\'.$name;
            if (class_exists($class_name)) {
                return \Gini\IoC::construct($class_name, $vars);
            }
        }

        return \Gini\IoC::construct('\Gini\Module\Widget', $name, $vars);
    }
}