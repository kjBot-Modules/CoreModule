<?php
namespace kjBotModule\kj415j45\CoreModule;

use kjBot\Framework\Plugin;
use kjBot\Framework\Event\BaseEvent;

class AccessControlPlugin extends Plugin{
    public $handleQueue = true;

    public function handle($event){
        $ac = new AccessControl($event);
        try{
            $ac->hasLevelOrDie(Config('ACLevel', AccessLevel::User));
        }catch(SilenceAccessDenied $e){
            SilenceAccessDenied::$silence = true;
            throw $e;
        }
    }

    public function beforePostMessage(&$queue){
        if(SilenceAccessDenied::$silence){
            _log('NOTICE', $queue[0]);
            $queue = NULL;
        }
    }
}