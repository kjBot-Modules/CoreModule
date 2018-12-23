<?php
namespace kjBotModule\kj415j45\CoreModule;

use kjBot\Framework\Plugin;
use kjBot\Framework\Event\BaseEvent;

class AccessControlPlugin extends Plugin{
    static $silence = false;

    public function handle($event){
        global $Config;
        $ac = new AccessControl($event);
        try{
            $ac->requireLevel($Config['ACLevel']??AccessLevel::User);
        }catch(AccessDeniedException $e){
            static::$silence = true;
            throw $e;
        }
    }

    public function beforePostMessage(&$queue){
        if(static::$silence){
            _log('NOTICE', $queue[0]);
            $queue = NULL;
        }
    }
}