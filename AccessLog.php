<?php
namespace kjBotModule\kj415j45\CoreModule;

use DateTime;
use kjBot\Framework\DataStorage;
use kjBot\Framework\Event\MessageEvent;
use kjBot\Framework\Module;

class AccessLog{
    const BaseDir = 'CoreModule.AccessLog/';

    public static function Log(Module $module, MessageEvent $event, string $note = ''){
        $moduleName = get_class($module);
        $groupId = $event->fromGroup()?" (In {$event->groupId})":'';
        $senderId = $event->getId();
        $time = (new DateTime('now'))->format('Y-m-d H:i:s');

        return DataStorage::SetData(static::BaseDir.$senderId, 
            "[{$time}]{$groupId} {$moduleName}: {$note}\n"
        , true);
    }

    public static function LogForModule(Module $module, string $note = ''){
        $moduleName = str_replace('\\', '.', get_class($module));
        $time = (new DateTime('now'))->format('Y-m-d H:i:s');

        return DataStorage::SetData(static::BaseDir.$moduleName,
            "[{$time}] {$note}\n"
        , true);
    }
}