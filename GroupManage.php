<?php
namespace kjBotModule\kj415j45\CoreModule;

use kjBot\SDK\CQCode;
use kjBot\Framework\DataStorage;

class GroupManage{
    public $groupId;
    public $_enableWelcomeMsg = true;
    protected $welcomeMsg = '@:uid 欢迎加入本群，请阅读群公告！';
    public $_enableLeaveMsg = true;
    protected $leaveMsg = ':uid 离开了本群';

    protected function __construct($groupId, $data = NULL){
        $this->groupId = $groupId;
        if($data !== NULL){
            $this->_enableWelcomeMsg = $data->enableWelcomeMsg;
            $this->welcomeMsg = $data->welcomeMsg;
            $this->_enableLeaveMsg = $data->enableLeaveMsg;
            $this->leaveMsg = $data->leaveMsg;
        }
    }

    public function enableWelcomeMsg(bool $b = true): GroupManage{
        $this->_enableWelcomeMsg = $b;
        return $this;
    }

    public function enableLeaveMsg(bool $b = true): GroupManage{
        $this->_enableLeaveMsg = $b;
        return $this;
    }

    public function setWelcomeMsg(string $msg): GroupManage{
        $this->welcomeMsg = $msg;
        return $this;
    }

    public function getWelcomeMsg($event): string{
        $msg = $this->welcomeMsg;
        $msg = str_replace('@:uid', CQCode::At($event->getId()), $msg);
        $msg = str_replace(':uid', $event->getId(), $msg);
        $msg = str_replace('@:oid', CQCode::At($event->operatorId), $msg);
        $msg = str_replace(':oid', $event->operatorId, $msg);
        return $msg;
    }

    public function setLeaveMsg(string $msg): GroupManage{
        $this->leaveMsg = $msg;
        return $this;
    }

    public function getLeaveMsg($event): string{
        $msg = $this->leaveMsg;
        $msg = str_replace('@:uid', CQCode::At($event->getId()), $msg);
        $msg = str_replace(':uid', $event->getId(), $msg);
        $msg = str_replace('@:oid', CQCode::At($event->operatorId), $msg);
        $msg = str_replace(':oid', $event->operatorId, $msg);
        return $msg;
    }

    protected function toJson(): string{
        return json_encode([
            'enableWelcomeMsg' => $this->_enableWelcomeMsg,
            'welcomeMsg' => $this->welcomeMsg,
            'enableLeaveMsg' => $this->_enableLeaveMsg,
            'leaveMsg' => $this->leaveMsg,
        ]);
    }

    protected static function Init($groupId): GroupManage{
        $gm = new GroupManage($groupId);
        $gm->save();
        return $gm;
    }

    public static function Load($groupId): GroupManage{
        $data = DataStorage::GetData('CoreModule.GroupManage/'.$groupId);
        if($data === false){
            return static::Init($groupId);
        }else{
            return new GroupManage($groupId, json_decode($data));
        }
    }

    public function save(){
        return DataStorage::SetData('CoreModule.GroupManage/'.$this->groupId, $this->toJson());
    }
}