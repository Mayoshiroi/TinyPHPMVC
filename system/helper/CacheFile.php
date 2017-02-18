<?php defined('ROOTPATH') OR exit();
/**
 * WARING!!! HERE IS A BUG IN PHP7.0
 * if you use  "getCache()","getKeyTime()" both,the php-fpm will crash.But its run well in php 5.6.10
 */
class CacheFile {
    
    protected $cachepath;
            
    public function __construct() {
        $this->cachepath = CACHEPATH;
        chmod($this->cachepath,0775);
    }
    
    /**
     * check R&W permission
     * @return type
     */
    public function checkPermission(){
        return fopen($this->cachepath.DIRECTORY_SEPARATOR."default.cachex", "w")?true:false;
    }
    
    /**
     * check file exists
     * @param string $filename
     * @return bool
     */
    private function checkFileExists($filename = "default.cachex"){
        return file_exists($this->cachepath.DIRECTORY_SEPARATOR.$filename);
    }
    
    /**
     * save cache 
     * @param string $key
     * @param object $value
     * @return boolean
     */
    public function saveCache($key,$value){
        $filename = $key.".cachex";
        //get file handle
        $cachefile = fopen($this->cachepath.DIRECTORY_SEPARATOR.$filename, "w");
        if($cachefile == FALSE){return false;}
        //check file lock
        if(flock($cachefile, LOCK_EX)){
            fwrite($cachefile, serialize($value));
            fclose($cachefile);
            
            return TRUE;
        }  else {
            fclose($cachefile);
            
            return FALSE;
        }
    }
    
    /**
     * get cache
     * @param string $key
     * @return object | bool
     */
    public function getCache($key){
        $filename = $key.".cachex";
        
        if($this->checkFileExists($filename)){
            $cachefile = fopen($this->cachepath.DIRECTORY_SEPARATOR.$filename, "r");
            if($cachefile == FALSE){return false;}
            if(flock($cachefile, LOCK_SH)){
                $value = fread($cachefile, filesize($this->cachepath.DIRECTORY_SEPARATOR.$filename));
                fclose($cachefile);
                return unserialize($value);
            }
        }else{
            return FALSE;
        }
    }
    
    /**
     * delete cache
     * @param type $key
     * @return boolean
     */
    public function deleteCache($key){
        $filename = $key.".cachex";
        
        if($this->checkFileExists($filename)){
            return unlink($this->cachepath.DIRECTORY_SEPARATOR.$filename);
        }else{
            return FALSE;
        }
    }
    
    /**
     * get create time or last edit time with key
     * @param string $key
     * @return int
     */
    public function getKeyTime($key){
        $filename = $key.".cachex";
        
        if($this->checkFileExists($filename)){
            $mtime = filemtime($this->cachepath.DIRECTORY_SEPARATOR.$filename);
            $ctime = filectime($this->cachepath.DIRECTORY_SEPARATOR.$filename);
            
            return $mtime > $ctime?$mtime:$ctime;
        }else{
            
            return FALSE;
        }
    }
    
    /**
     * 
     * @return type
     */
    public function getAllKeys(){
        $dir = dir(CACHEPATH.DIRECTORY_SEPARATOR);
        
        $keys = array();
        while($file = $dir->read()){
            if(strstr($file, ".cachex")){
                $keys[] = explode('.', $file)[0];
             }
        }
        
        return $keys;
    }
}
