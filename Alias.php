<?php
namespace kjBotModule\kj415j45\CoreModule;

use kjBot\Framework\DataStorage;


class Alias{
    protected $id;

    public function __construct($id){
        $this->id = $id;
    }

    public function setAlias($source, $target){
        $alias = $this->getAlias();
        $alias[$source]= $target;
        return $this->save($alias);
    }

    public function getAlias($source = NULL){
        $alias = json_decode(DataStorage::GetData('CoreModule.Alias/'.$this->id), true);
        if($source == NULL)return $alias;
        else{
            if(isset($alias[$source])){
                return $alias[$source];
            }
        }
        return NULL;
    }

    public function delAlias($source){
        $alias = $this->getAlias();
        unset($alias[$source]);
        return $this->save($alias);
    }

    private function save($alias){
        return DataStorage::SetData('CoreModule.Alias/'.$this->id, json_encode($alias));
    }
}