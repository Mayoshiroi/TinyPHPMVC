<?php defined('ROOTPATH') OR exit();

class CoreController {
    
    protected $input;
    
    public function __construct() {
        foreach ($_POST as $key => $value) {
            $this->input["post"][] = array(fix_xss($key),fix_xss($value));
        }
        foreach ($_GET as $key => $value) {
             $this->input["get"][] = array(fix_xss($key),fix_xss($value));
        }
    }

    /**
     * Load view file
     * @param type $viewpath view file path
     * @param type $viewvariable variable in view file
     */
    
    protected function view($viewpath,$viewvariable=  array()){
        
        foreach ($viewvariable as $key => $value) {
            $$key = $value;
        }
        
        if(file_exists(VIEWPATH.DIRECTORY_SEPARATOR.$viewpath.'.php')){
            include_once VIEWPATH.DIRECTORY_SEPARATOR.$viewpath.'.php';
        }
    }
    
    /**
     * output with conv xss string
     * @param string $echo
     * @param bool $flag
     */
    protected function output($echo,$flag=true){
        if($flag){
            echo fix_xss($echo);
        }
        else {
            echo $echo;
        }
    }

     /**
     * conv xss string
     * @param string $input
     * @return string
     */
    protected function fix_xss($input){
         return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}
