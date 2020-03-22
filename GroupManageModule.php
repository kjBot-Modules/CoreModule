<?php
namespace kjBotModule\kj415j45\CoreModule;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\Framework\DataStorage;

class GroupManageModule extends Module{
    public function process(array $args, $event){
        if(!($event instanceof GroupMessageEvent)){
            q('只有群聊才能使用本命令');
        }
        $ac = Access::Control($event);
        if($ac->isGroup('admin') || $ac->isGroup('owner')){
            switch($args[0]){
                case '设置欢迎消息':
                case '设置入群消息':
                case '/welcomeMsg':
                case '!welcomeMsg':
                case '！welcomeMsg':
                    return $this->setWelcomeMsg($args, $event);
                case '设置退群提示':
                case '/leaveMsg':
                case '!leaveMsg':
                case '！leaveMsg':
                    return $this->setLeaveMsg($args, $event);
                case '启用入群消息':
                    return (GroupManage::Load($event->groupId)->enableWelcomeMsg()->save())!==false?
                        $event->sendBack('设置成功'):q('设置失败');
                case '禁用入群消息':
                    return (GroupManage::Load($event->groupId)->enableWelcomeMsg(false)->save())!==false?
                        $event->sendBack('设置成功'):q('设置失败');
                case '启用退群提示':
                    return (GroupManage::Load($event->groupId)->enableLeaveMsg()->save())!==false?
                        $event->sendBack('设置成功'):q('设置失败');
                case '禁用退群提示':
                    return (GroupManage::Load($event->groupId)->enableLeaveMsg(false)->save())!==false?
                        $event->sendBack('设置成功'):q('设置失败');
                default:
                    q('未知的匹配模式');
            }
        }else{
            q('只有管理员可以进行此操作');
        }
    }

    protected function setWelcomeMsg(array $args, GroupMessageEvent $event){
        $gm = GroupManage::Load($event->groupId);
        $msg = substr(strstr($event->getMsg(), "\n"), 1);
        if($msg === false){
            q('请从第二行开始输入欢迎消息');
        }else{
            $gm->setWelcomeMsg($msg)->save();
            return $event->sendBack('设置成功');
        }
    }

    protected function setLeaveMsg(array $args, GroupMessageEvent $event){
        $gm = GroupManage::Load($event->groupId);
        $msg = substr(strstr($event->getMsg(), "\n"), 1);
        if($msg === false){
            q('请从第二行开始输入退群提示');
        }else{
            $gm->setLeaveMsg($msg)->save();
            return $event->sendBack('设置成功');
        }
    }
}
