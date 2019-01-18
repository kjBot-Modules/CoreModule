<?php
namespace kjBotModule\kj415j45\CoreModule;

use kjBot\Framework\Plugin;
use kjBot\Framework\Event\GroupIncreaseEvent;
use kjBot\Framework\Event\GroupDecreaseEvent;
use kjBot\Framework\DataStorage;

class GroupManagePlugin extends Plugin{
    public $handleDepth = 2;

    public function notice_group_increase(GroupIncreaseEvent $event){
        $gm = GroupManage::Load($event->groupId);
        if($gm->_enableWelcomeMsg){
            switch($event->subType){
                case 'approve':
                case 'invite':
                    if(\Config('self_id') == $event->getId()){
                        $msg = DataStorage::GetData('GroupManagerIntroduce.txt');
                    }else{
                        $msg = $gm->getWelcomeMsg($event);
                    }
                    break;
                default:
                    p('未知的入群事件：'.export($event));
            }
        }
        return $event->sendBack($msg);
    }

    public function notice_group_decrease(GroupDecreaseEvent $event){
        $gm = GroupManage::Load($event->groupId);
        if($gm->_enableLeaveMsg){
            switch($event->subType){
                case 'leave':
                case 'kick':
                    $msg = $gm->getLeaveMsg($event);
                    break;
                case 'kick_me':
                    return;
                default:
                    p('未知的退群事件：'.export($event));
            }
        }
        return $event->sendBack($msg);
    }
}