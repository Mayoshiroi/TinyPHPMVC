<?php defined('ROOTPATH') OR exit();

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SessionSQL
 *
 * @author XuuuA
 */
class SessionSQL implements SessionHandlerInterface, SessionIdInterface{
    
    private $fich;
    private $db;
    private $tablename;

    public function __construct() {
        
        $this->db =  CoreModel::getDBDriver();
        $this->tablename = "session";
                
    }
    
   public function destroy ($session_id){
       
        return $this->db->delete($this->tablename )->where(array("sessionid"=>$session_id))->rowCount() > 0?TRUE:FALSE;
               
   }
   
   public function close(){
       
       $this->db->clear();
       return FALSE;

   }

   public function open ($save_path, $name){
       return TRUE;
   }
   
   public function write ($session_id, $session_data){
       
       $session = $this->db->select($this->tablename )->where(array("sessionid"=>$session_id))->rowResult();
       
       if(empty($session)){
           return $this->db->insert($this->tablename , array("sessionid"=>$session_id,"sessiondata"=>$session_data))->rowCount() > 0?TRUE:FALSE;
       }else{
           if($session["sessiondata"] != $session_data){
                return $this->db->update($this->tablename , array("sessiondata"=>$session_data))->where(array("id"=>$session["id"]))->rowCount() > 0?TRUE:FALSE;
           }
           return TRUE;
       }
        
   }
   
   public function read ($session_id){
       
       $result = $this->db->select($this->tablename )->where(array("sessionid"=>$session_id))->rowResult();
       if(empty($result)){
           return FALSE;
       }else{
           $this->db->update($this->tablename , array("lasttime"=>  date("Y-m-d H:i:s", time())))->where(array("id"=>$result["id"]))->rowCount();
           return $result["sessiondata"];
       }
       
   }
   
   public function gc($maxlifetime) {
       return $this->db->delete($this->tablename)->where(array("lasttime"=>  date("Y-m-d H:i:s",(time()-$maxlifetime))),"<")->rowCount()!=0?TRUE:FALSE;
   }

   public function create_sid() {
       
       return sha1(time()+rand(10, 10000));
       
   }
}
