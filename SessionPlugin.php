<?php
namespace kjBotModule\kj415j45\CoreModule;

use kjBot\Framework\Plugin;
use kjBot\Framework\Event\MessageEvent;
use kjBot\Framework\DataStorage;
use kjBot\Framework\Message;
use kjBot\Framework\QuitException;
use kjBot\Framework\SilenceModule;

class SessionPlugin extends Plugin{
    public $handleDepth = 1;

    public function message(MessageEvent $event): ?Message{
        $session = json_decode(DataStorage::GetData("CoreModule.Session/{$event->getId()}"));
        if($session !== NULL){
            SilenceModule::$silence = true;
            try{
                $obj = (new \ReflectionClass($session->class))->newInstance();
                $method = new \ReflectionMethod($session->class, $session->method);
                return $msg = $method->invoke($obj, $event);
            }catch(\ReflectionException $e){
                d("Reflect error: {$e->getMessage()}");
                q('反射你的会话文件失败，请联系master');
            }catch(QuitException $e){
                throw $e;
            }
        }else return NULL;
    }
}