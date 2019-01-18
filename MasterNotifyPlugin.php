<?php
namespace kjBotModule\kj415j45\CoreModule;

use kjBot\SDK\CoolQ;
use kjBot\Framework\Plugin;
use kjBot\Framework\Event\FriendRequestEvent;
use kjBot\Framework\Event\InvitedToGroupEvent;


class MasterNotifyPlugin extends Plugin{
    public $handleDepth = 3;

    const cq_request_friend = true;
    public function coolq_request_friend(FriendRequestEvent $event, CoolQ $cq){
        $uid = $event->getId();
        $comment = $event->comment;
        $flag = $event->getFlag();

        if(Config('AllowFriends', false)){
            try{
                $event->accept($cq);
            }catch(\Exception $e){
                _log('ERROR', export($e));
                return notifyMaster("自动添加 {$uid} 为好友失败，备注信息为“{$comment}”。flag为\n{$flag}");
            }
            return notifyMaster("已自动添加 {$uid} 为好友，备注信息为“{$comment}”");
        }else{
            return notifyMaster("{$uid} 请求添加好友，备注信息为“{$comment}”。flag为\n{$flag}");
        }
    }

    const cq_request_group_invite = true;
    public function coolq_request_group_invite(InvitedToGroupEvent $event, CoolQ $cq){
        $uid = $event->getId();
        $gid = $event->groupId;
        $flag = $event->getFlag();

        if(Config('AllowGroups', false)){
            try{
                $event->accept($cq);
            }catch(\Exception $e){
                _log('ERROR', export($e));
                return notifyMaster("自动接受 {$uid} 邀请到群 {$gid} 的请求失败。flag为\n{$flag}");
            }
            return notifyMaster("已自动接受 {$uid} 邀请到群 {$gid} 的请求");
        }else{
            return notifyMaster("{$uid} 请求添加到群 {$gid}，flag为\n{$flag}");
        }
    }
}