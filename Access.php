<?php
namespace kjBotModule\kj415j45\CoreModule;

class Access{
    public final static function Control($event): AccessControl{
        return new AccessControl($event);
    }
}
