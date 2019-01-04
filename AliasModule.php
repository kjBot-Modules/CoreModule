<?php
namespace kjBotModule\kj415j45\CoreModule;

use kjBot\Framework\Module;
use kjBot\Framework\Message;
use kjBot\Framework\Event\MessageEvent;

class AliasModule extends Module{

    public function process(array $args, MessageEvent $event): Message{
        Access::Control($event)->hasLevelOrDie(AccessLevel::Supporter);
        return $event->sendBack(
            Session::Start($event->getId())
                   ->prompt('请输入要设置的别名：', __CLASS__, 'setAliasName')
        );
    }

    public function setAliasName($event){
        (new Alias($event->getId()))->setAlias($event->getMsg(), NULL);
        return $event->sendBack(
            Session::Start($event->getId())
                   ->prompt('请输入要映射的命令：', __CLASS__, 'setAlias')
        );
    }

    public function setAlias($event){
        $alias = new Alias($event->getId());
        $aliasName = array_search(NULL, $alias->getAlias());
        $alias->setAlias($aliasName, $event->getMsg());
        Session::Stop($event->getId());
        return $event->sendBack('设置成功');
    }

}