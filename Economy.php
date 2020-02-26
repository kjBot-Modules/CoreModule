<?php
namespace kjBotModule\kj415j45\CoreModule;

use Exception;
use kjBot\Framework\DataStorage;

class Economy{
    const NO_MONEY = -1; //余额不足
    const UNEXPECTED_NEGATIVE_NUMBER = -2; //未期望的负值
    const STR_NO_MONEY_HINT = '金币不足，可通过签到等方式获取';

    const BaseDir = 'CoreModule.Economy/';
    private $user;
    private $balance;

    public function __construct($user){
        $this->user = $user;
        $this->balance = DataStorage::GetData(static::BaseDir.$user);
    }

    public static function setBalance($user, int $balance){
        return DataStorage::SetData(static::BaseDir.$user, $balance);
    }

    protected function save(){
        return DataStorage::SetData(static::BaseDir.$this->user, $this->balance);
    }

    public function addBalance(int $income){
        if($income<=0) throw new Exception('入账不能为负或零', static::UNEXPECTED_NEGATIVE_NUMBER);

        $this->balance += $income;
        $this->save();
        Access::LogForMe($this, "{$this->user} + {$income}");
        return $this;
    }

    public function decBalance(int $expend){
        if($expend<=0) throw new Exception('支出不能为负或零', static::UNEXPECTED_NEGATIVE_NUMBER);

        if($this->balance < $expend) throw new Exception('没有足够的余额', static::NO_MONEY);

        $this->balance -= $expend;
        $this->save();
        Access::LogForMe($this, "{$this->user} - {$expend}");
        return $this;
    }

    public function transfer(Economy $target, int $money){
        if($money<=0) throw new Exception('转账金额不能为负或零', static::UNEXPECTED_NEGATIVE_NUMBER);
        try{
            $this->decBalance($money);
        }catch(Exception $e){
            throw $e;
        }
        $target->addBalance($money);

        return $this;
    }
}