<?php
namespace kjBotModule\kj415j45\CoreModule;

use kjBot\SDK\CoolQ;
use kjBot\Framework\Module;
use kjBot\Framework\Message;
use kjBot\Framework\Event\MessageEvent;
use kjBotModule\kj415j45\CoreModule\Access;

class AccessControlModule extends Module{
    public function process(array $args, MessageEvent $event): Message{
        $ac = Access::Control($event);
        $ac->hasLevelOrDie(AccessLevel::Master);
        switch($args[1]??q('缺少操作方法')){
            case 'set':
            case '设置':
                $id = \parseQQ($args[2])??q('缺少 QQ 号');
                $level = $args[3]??q('缺少权限级别');
                $expire = $args[4]??q('缺少到期时间');
                if($ac->setLevelFor($id, $level, $expire)){
                    return $event->sendBack("成功将 {$id} 的权限级别设置为 {$level}，到期时间是 ".(new \DateTime($expire))->format('Y-m-d H:i:s'));
                }else{
                    q('设置失败');
                }
            case 'get':
            case '取得':
                $id = \parseQQ($args[2]??q('缺少 QQ 号'));
                return $event->sendBack("{$id} 的权限级别为 {$ac->getLevel()}");
            default:
                break;
        }
    }
}