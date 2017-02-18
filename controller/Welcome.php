<?php defined('ROOTPATH') OR exit();
class Welcome extends MyController{
    
    function __construct() {
        parent::__construct();
        
    }
            
    function index(){
        //$db = new DBDriver();
        //var_dump($db->select("main")->where(array("id"=>1))->orwhere(array("id"=>2))->where(array("name"=>"totori"))->result());
        //echo $db->insert("main", array("name" => "xx","age"=>12))->rowCount();
        //echo $db->update("main", array("age"=>3))->where(array("age"=>2))->orwhere(array("age"=>12))->rowCount();
        //echo $db->delete("main")->where(array("age"=>3))->orwhere(array("age"=>31))->rowCount();
        //$cache = new CacheHelper();
        //$cache->saveCache("xxx", "asd");
        //echo $cache->getCache("xxx")."<br>";
        //$cache->gc(60);
        
        
        $this->view("welcome", array('title'=>'你好'));
    }
    
}