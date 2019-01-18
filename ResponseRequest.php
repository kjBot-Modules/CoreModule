<?php
namespace kjBotModule\kj415j45\CoreModule;

use kjBot\SDK\CoolQ;
use kjBot\Framework\Module;
use kjBot\Framework\Event\PrivateMessageEvent;

class ResponseRequest extends Module{
    public $needCQ = true;

    public function processWithCQ(array $args, $event, CoolQ $cq = NULL){
        if($event instanceof PrivateMessageEvent){
            Access::Control($event)->hasLevelOrDie(AccessLevel::Master); //只有 Master 有权限操作

            $group = false;
            $friend = false;
            
            switch($args[1]??q('提供目标类型')){
                case 'group':
                case 'Group':
                case '群':
                    $group = true;
                    break;
                case 'friend':
                case 'Friend':
                case '好友':
                    $friend = true;
                    break;
            }

            switch($args[0]){
                case '接受':
                case '接受请求':
                case 'Accept':
                case 'accept':
                    $accept = true;
                    break;
                case '拒绝':
                case '拒绝请求':
                case 'Deny':
                case 'deny':
                    $accept = false;
                    break;
            }

            if($group){
                try{
                    $cq->setGroupAddRequest($args[2]??q('未提供flag'), 'invite', $accept, $args[3]??'');
                }catch(\Exception $e){
                    return notifyMaster('处理请求时发生错误：'.$e->getCode());
                }
                return notifyMaster('成功加群');
            }else if($friend){
                try{
                    $cq->setFriendAddRequest($args[2]??q('未提供flag'), $accept, $args[3]??'');
                }catch(\Exception $e){
                    return notifyMaster('处理请求时发生错误：'.$e->getCode());
                }
                return notifyMaster('成功加好友');
            }else{
                return notifyMaster('未匹配的响应模式');
            }
        }else{return;} //非私聊不响应
    }
}