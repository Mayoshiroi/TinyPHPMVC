<?php defined('ROOTPATH') OR exit();

class CookieHelper {
    

    public function setCokkie($name,$value,$expire,$path="",$domain="",$secure = false, $httponly = false){
        
        setcookie($name,$value,$expire,$path,$domain,$secure,$httponly);
        
    }
    
    public function getCokkie($name){
        
        return isset($_COOKIE[$name])?$_COOKIE[$name]:FALSE;
        
    }
    
    public function deleteCookie($name){
        
        setcookie($name,"" , time()-3600);
        
    }
}
