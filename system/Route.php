<?php defined('ROOTPATH') OR exit();

$route['default'] = 'Welcome';

$pathinfo = isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'/'.$route['default'];
$pathroute = explode('/', $pathinfo);
$pathroute[1] = empty($pathroute[1])?$route['default']:$pathroute[1];// if the url is "domain/index.php/" use default controller
parsePathinfo($pathroute);

/**
 * 解析PATH数组让其自动载入控制器
 * @param array $_route Route array
 * @param string $path Controller path
 * @param string $default Default controller name
 */
function parsePathinfo($_route,$path='',$default='Welcome'){
    for ($i=1;$i<count($_route);$i++){
        $pathroot = strtolower($_route[$i]);
        if(is_dir(CONTROLLEPATH.DIRECTORY_SEPARATOR.$path.DIRECTORY_SEPARATOR.$pathroot)){//
            $path = $path.DIRECTORY_SEPARATOR.$pathroot;
            parsePathinfo($path, $_route);
        }else{
            $controller = $_route[$i];
            $method = ($i+1)<count($_route)?$_route[$i+1]?$_route[$i+1]:'index':'index';
            $path = !$path?$controller:$path.DIRECTORY_SEPARATOR.$controller;
            loadController($path, $controller, $method);
            $controllerclass = new $controller();
            $controllerclass->$method(); 
            break;
        }
    }
}

/**
 * Load controller use require_once 
 * @param type $path Controller floder path
 * @param type $classname Controller class name
 * @param type $methodname function name
 * @return boolean is legal
 * @throws ErrorException errors when check class and function isn't legal
 */
function loadController($path,$classname,$methodname){//check and require controller file
    $classname = ucfirst($classname);
    
    $cheakfile = file_exists(CONTROLLEPATH.DIRECTORY_SEPARATOR.$path.'.php');
    if(!$cheakfile){
        throw new ErrorException("Can'n Find Controoler File");
    }
    require_once CONTROLLEPATH.DIRECTORY_SEPARATOR.$path.'.php';
    
    $cheakclass = class_exists($classname);
    if(!$cheakclass){
        throw new ErrorException("Class Not Exists!");
    }
    
    $class = new $classname();
    
    $checkmethod = method_exists($class,$methodname);
    if(!$checkmethod){
        throw new ErrorException("Can'n Find Method Name!");
    }
    return true;
}