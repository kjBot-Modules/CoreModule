<?php
namespace kjBotModule\kj415j45\CoreModule;

use kjBot\Framework\PanicException;

class SilenceAccessDenied extends PanicException{
    var $prompt='Access Denied';
    static $silence = false;
    public function __construct(){
        parent::__construct();
        static::$silence = true;
    }
}
