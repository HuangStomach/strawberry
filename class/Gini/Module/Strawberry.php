<?php

namespace Gini\Module {

    class Strawberry {

        static function setup() {
            date_default_timezone_set(\Gini\Config::get('system.timezone') ?: 'Asia/Shanghai');
            class_exists('\Gini\Those');
            setlocale(LC_MONETARY, \Gini\Config::get('system.locale') ?: 'zh_CN');
            \Gini\I18N::setup();

            if (PHP_SAPI == 'cli') return;
            
            if (\Gini\Auth::isLoggedIn()) {
                $username = \Gini\Auth::username();
                $me = a('user')->whose('username')->is($username);
                _G('ME', $me);
            }
        }

    }
}
