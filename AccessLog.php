<?php
namespace kjBotModule\kj415j45\CoreModule;

use DateTime;
use kjBot\Framework\DataStorage;
use kjBot\Framework\Event\MessageEvent;

class AccessLog{
    const BaseDir = 'CoreModule.AccessLog/';

    public static function Log($instance, MessageEvent $event, string $note = ''){
        $className = get_class($instance);
        $groupId = $event->fromGroup()?" (In {$event->groupId})":'';
        $senderId = $event->getId();
        $time = (new DateTime('now'))->format('Y-m-d H:i:s');

        return DataStorage::SetData(static::BaseDir.$senderId, 
            "[{$time}]{$groupId} {$className}: {$note}\n"
        , true);
    }

    public static function LogForModule($instance, string $note = ''){
        $className = str_replace('\\', '.', get_class($instance));
        $time = (new DateTime('now'))->format('Y-m-d H:i:s');

        return DataStorage::SetData(static::BaseDir.$className,
            "[{$time}] {$note}\n"
        , true);
    }
}