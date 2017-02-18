<?php

class CoreModel {
    
    protected $dbdriver;
    
    public function __construct() {
        $this->dbdriver = CoreModel::getDBDriver();
    }

    public static function getDBDriver(){
        if(!isset($GLOBALS["DBDRIVER"])){
            $GLOBALS["DBDRIVER"] = new DBDriver();
        }
        return $GLOBALS["DBDRIVER"];
    }
}
