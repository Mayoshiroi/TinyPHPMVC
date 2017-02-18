<?php defined('ROOTPATH') OR exit();

class MyController extends CoreController{
    
    /**
     * Default cookie expiration time
     * @var int 
     */
    protected $cookietime;
    
    /**
     * CookieHelper object
     * @var CookieHelper 
     */
    protected $cookiehelper;
    
    /**
     * SessionHelper object
     * @var SessionHelper 
     */
    protected $sessionhelper;
    
    /**
     * CacheHelper
     * @var CacheHelper 
     */
    protected $cachehelper;


    public function __construct() {
        
        $this->cookietime = time()+60*60*24*30;
        $this->cookiehelper = new CookieHelper();
        $this->sessionhelper = new SessionHelper();
        $this->cachehelper = new CacheHelper();
        
        parent::__construct();
        
    }
}
