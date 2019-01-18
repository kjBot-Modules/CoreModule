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

        $matches = parseCommand($event->getMsg());
        $command = rtrim($matches[0]);
        if($matches==NULL){
            $command = $event->getMsg();
            $matches = [$command];
        }
        $aliasTarget = $aliasList[$command];
        if($aliasTarget==NULL)return;
        $str = $event->getMsg();
        d('Before alias: '.$str);
        $str = $aliasTarget;
        $argCount = count($matches);
        $pending = [];
        for($i = 1; $i<$argCount; $i++){
            if(strpos($str, ':arg'.$i)===false){
                $pending[]= $matches[$i];
                d(':arg'.$i.' not set, will be appended to tail');
            }else{
                $str = str_replace_once(':arg'.$i, $matches[$i], $str);
                d('Replace :arg'.$i.': '.$str);
            }
        }
        $str.= ' '.implode(' ', $pending);
        d('After alias: '.$str);
        $event->setMsg($str);
    }
}
