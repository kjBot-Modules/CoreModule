<?php
namespace kjBotModule\kj415j45\CoreModule;

use kjBot\Framework\Module;
use kjBot\Framework\Message;
use kjBot\Framework\Event\MessageEvent;

class DelAlias extends Module{
    public function process(array $args, MessageEvent $event): Message{
        $alias = new Alias($event->getId());
        $alias->delAlias($args[1]);
        return $event->sendBack('删除别名：'.$args[1].' 成功');
    }
}