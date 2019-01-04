<?php
namespace kjBotModule\kj415j45\CoreModule;

use kjBot\Framework\Plugin;
use kjBot\Framework\Event\MessageEvent;

class AliasPlugin extends Plugin{
    public $handleDepth = 1;

    public function message(MessageEvent $event){
        if(!Access::Control($event)->hasLevel(AccessLevel::Supporter))return;
        $alias = new Alias($event->getId());

        $aliasList = $alias->getAlias();
        if($aliasList==NULL)return;
        if(array_search(NULL, $aliasList))return;

        $matches = preg_split('/\s+/', $event->getMsg());
        $command = rtrim($matches[0]);
        if($matches==NULL){
            $command = $event->getMsg();
            $matches = [$command];
        }
        $aliasTarget = $aliasList[$command];
        if($aliasTarget==NULL)return;
        $str = $event->getMsg();
        d('Before alias: '.$str);
        $str = (\str_replace_once($command, $aliasTarget, $str));
        $argCount = count($matches);
        for($i = 1; $i<$argCount; $i++){
            $str = str_replace_once(':arg'.$i, $matches[$i], $str);
        }
        $event->setMsg($str);
        d('After alias: '.$str);
    }
}