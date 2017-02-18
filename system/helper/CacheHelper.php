<?php defined('ROOTPATH') OR exit();

class CacheHelper {
    
    /**
     * CacheDriver handle
     * @var CacheFile 
     */
    protected $handle;
    
    public function __construct($handle = "") {
        if($handle == ""){
            $this->handle = new CacheFile();
        }
    }
    
    public function getHandle(){
        return $this->handle;
    }

    public function set($key,$value){
        return $this->handle->saveCache($key, $value);
    }
    
    public function get($key){
        return $this->handle->getCache($key);
    }
    
    public function gc($maxtime){
        $time = time() - $maxtime;
        
        $keys = $this->handle->getAllKeys();
        foreach ($keys as $key) {
            
            if(strstr($key, "session_c")) {//ignore session cache file
                continue;
            }
            
            $keytime = $this->handle->getKeyTime($key);
            if($keytime < $time){
                $this->handle->deleteCache($key);
            }
        }
        
        return true;
    }
    
    public function destory(){
         $keys = $this->handle->getAllKeys();
         foreach ($keys as $key) {
             $this->handle->deleteCache($key);
         }
    }
        
}
