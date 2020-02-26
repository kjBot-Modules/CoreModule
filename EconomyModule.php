<?php
namespace kjBotModule\kj415j45\CoreModule;

use kjBot\Framework\Module;
use kjBot\Framework\Event\MessageEvent;

class EconomyModule extends Module{
    public function process(array $args, MessageEvent $event){
        $economy = new Economy($event->getId());
        $balance = $economy->getBalance();
        switch($args[0]){
            case '余额':
                return $event->sendBack("你的余额为 {$balance}");
            
            default:
                return $event->sendBack('未知操作');
        }
    }
}