<?php defined('ROOTPATH') OR exit();

class SessionHelper {
    
    private $handle ;
    
    public function __construct($handle = "") {
        if($handle == ""){
            $this->handle = new SessionFile();
        }
        
        session_name("sessionid");
        session_set_save_handler($this->handle, true);
        if(PHP_SESSION_NONE == session_status()){
            session_start();
        }
    }
    
    public function getHandle(){
        return $this->handle;
    }
    
    public function setSession($key,$value){
        $_SESSION[$key] = $value;
    }
    
    public function getSession($key){
        
        return $_SESSION[$key];
    }
    
    public function destroy(){
        session_destroy();
    }
    
    public function gc($maxlifetime){
        return $this->handle->gc($maxlifetime);
    }
    
    public function isRegistered($key){
        
        return isset($_SESSION[$key]);
    }
    
    public function unsetSession($key){
        unset($_SESSION[$key]);
    }
    
    public function unsetAll(){
        session_unset();
    }
}

