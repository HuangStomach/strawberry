<?php

namespace Gini\Controller\CGI\AJAX\Index;

class Site extends \Gini\Controller\CGI {

    public function __index() {
        $sites = those('site')->whose('show')->is(true);

        $view = V('home/index/site', [
            'sites' => $sites
        ]);

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', $view);
    }

}