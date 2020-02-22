<?php
namespace kjBotModule\kj415j45\CoreModule;

use kjBot\Framework\Module;
use kjBot\Framework\Event\MessageEvent;

class Access{
    public final static function Control($event): AccessControl{
        return new AccessControl($event);
    }

    public final static function Log(Module $module, MessageEvent $event, string $note = ''){
        return AccessLog::Log($module, $event, $note);
    }

    public final static function LogForMe($module, string $note = ''){
        return AccessLog::LogForModule($module, $note);
    }
}
