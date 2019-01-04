<?php
namespace kjBotModule\kj415j45\CoreModule;

use kjBot\Framework\Message;
use kjBot\Framework\DataStorage;

class Session{

    protected $id;

    public function prompt(string $str, $callbackClass, $callbackMethod): string{
        $sessionFile = "CoreModule.Session/{$this->id}";
        DataStorage::SetData($sessionFile, json_encode([
            'class' => $callbackClass,
            'method' => $callbackMethod,
        ]));
        return $str;
    }

    public function __construct($id){
        $this->id = $id;
    }

    public static function Start($id): Session{
        return new Session($id);
    }

    public static function Stop($id){
        DataStorage::DelData("CoreModule.Session/{$id}");
    }
}