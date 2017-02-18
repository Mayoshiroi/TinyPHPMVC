<?php defined('ROOTPATH') OR exit();

class SessionFile implements SessionHandlerInterface, SessionIdInterface{
    
    private $filedriver;

    public function __construct() {
        $this->filedriver = new CacheFile();
     }

    public function close() {
        
    }

    public function create_sid() {
        return sha1(time()+rand(10, 10000));
    }

    public function destroy($session_id) {
        $this->filedriver->deleteCache("session_c".$session_id);
    }

    public function gc($maxlifetime) {
        $allkeys = $this->filedriver->getAllKeys();
        foreach ($allkeys as $value) {
            if(strstr($value, "session_c"))
            {
                $time = $this->filedriver->getKeyTime($value);
                if( (time() - $maxlifetime) > $time)
                {
                    $this->filedriver->deleteCache($value);
                }
            }
        }
    }

    public function open($save_path, $name) {
        return $this->filedriver->checkPermission();
    }

    public function read($session_id) {
        return $this->filedriver->getCache("session_c".$session_id);
    }

    public function write($session_id, $session_data) {
        return $this->filedriver->saveCache("session_c".$session_id, $session_data);
    }

}
