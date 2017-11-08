<?php

namespace Gini\Controller\CGI\AJAX\Index;

class Link extends \Gini\Controller\CGI {

    public function actionChannel() {
        $channels = those('link')->whose('type')->is(
            \Gini\ORM\Link::TYPE_CHANNEL
        )->limit(0, 2);

        $view = V('home/index/channel', [
            'channels' => $channels
        ]);

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', $view);
    }

}