<?php
namespace kjBotModule\kj415j45\CoreModule;

use kjBot\Framework\Event\BaseEvent;
use kjBot\Framework\DataStorage;
use kjBot\Framework\Event\MessageEvent;
use kjBotModule\kj415j45\CoreModule\SilenceAccessDenied;

class AccessControl{
    private $id;
    private $level = AccessLevel::User;
    private $expire = '99991231235959';
    private $role;
    private $defaultMaster;

    public function __construct($event){
        global $Config;
        $this->defaultMaster = $Config['master'];
        $this->id = $event->getId();
        if($event instanceof MessageEvent){
            $this->role = $event->getSender()->role;
        }
    }

    private function setLevelData($qq, int $level, $expire): bool{
        if($this->is(AccessLevel::Master) || $this->defaultMaster == $this->id){
            _log('NOTICE', "Set {$qq}'s Level to {$level} (($expire})");
            $data = json_encode([
                'level' => $level,
                'expire' => (new \DateTime($expire))->format('YmdHis'),
            ]);
            return DataStorage::SetData('CoreModule.AccessLevel/'.$qq, $data);
        }else throw new AccessDenied("该操作需要4级的权限");
    }

    public function setLevelFor($qq, int $level, $expire): bool{
        return $this->setLevelData($qq, $level, $expire);
    }

    private function getLevelData(): array{
        $level = DataStorage::GetData('CoreModule.AccessLevel/'.$this->id);
        if($level === false){
            $this->initLevelData();
            return ['level'=>$this->level, 'expire'=>$this->expire];
        }else return json_decode($level, true);
    }

    public function getLevel(): int{
        $uLevel =  $this->getLevelData();
        if($this->levelExpired($uLevel)){
            return AccessLevel::User;
        }else return $uLevel['level'];
    }

    private function levelExpired($uLevel){
        if(((new \DateTime())->format('YmdHis') - $uLevel['expire']) > 0){
            $this->initLevelData();
            return true;
        }else return false;
    }

    private function initLevelData(){
        $this->level = AccessLevel::User;
        $this->expire = '99991231235959';
        $data = json_encode([
            'level' => AccessLevel::User,
            'expire' => PHP_INT_MAX,
        ]);
        return DataStorage::SetData('CoreModule.AccessLevel/'.$this->id, $data);
    }

    public function hasLevel(int $level){
        return $this->getLevel() >= $level;
    }

    public function requireLevel(int $level){
        if(!$this->hasLevel($level))throw new AccessDenied("该操作需要{$level}级的权限");
    }

    public function hasLevelOrDie(int $level){
        if(!$this->hasLevel($level))throw new SilenceAccessDenied("该操作需要{$level}级的权限");
    }

    public function is(int $level): bool{
        return $this->getLevel() == $level;
    }

    public function isGroup(string $role): bool{
        return $this->role === $role;
    }
}