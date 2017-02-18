<?php defined('ROOTPATH') OR exit();

class DBDriver {
    
    protected $config;
    private $sqlstring = '';
    private $paramarray=array();
    private $whereflag = false;
    
    private $db;


    public function __construct() {
        
        require CONFIGPATH.DIRECTORY_SEPARATOR.'DBConfig.php';
        $this->config = $DBConfig;
        $driver = $DBConfig["driver"];
        
        if($driver == "pdomysql"){
            $this->db = new PdoMysql($this->config);
        }
        
    }
    
    /**
     * Start a select sentence
     * @param type $tablename
     * @param type $selectarray
     * @return \DBDriver
     */
    public function select($tablename,$selectarray = array()){
        $this->clear();
        $select = "*";
        if(!empty($selectarray)){
            $select = implode(',', $selectarray);
        }
        
        $this->sqlstring = sprintf("select %s from `%s`",$select,$tablename);
        
        return $this;
    }
    
    /**
     * Start update sentence
     * @param type $tablename
     * @param type $values
     * @return \DBDriver
     */
    public function update($tablename,$values){
        $this->clear();
        $updateparam = array();
        foreach ($values as $key => $value) {
            $this->paramarray[] = $value;
            $updateparam[] = $key." = ? ";
        }
        $this->sqlstring = sprintf("update `%s` set %s",$tablename, implode(',', $updateparam));
        
        return $this;
    }
    
    /**
     * Start insert sentence
     * @param type $tablename
     * @param type $insertvalues
     * @return \DBDriver
     */
    public function insert($tablename,$insertvalues){
        $this->clear();
        $transferparam = array();
        if(array_key_exists(0,$insertvalues)){
            for($i = 0;$i < count($insertvalues);$i++){
                $transferparam[$i] = '?';
                $this->paramarray[] = $insertvalues[$i];
            }
            $this->sqlstring = sprintf("insert into `%s` values (%s)",$tablename,  implode(',', $transferparam));
        }else{
            $keys = $values = array();
            foreach ($insertvalues as $key => $value) {
                $keys[] = $key;
                $transferparam[] = '?';
                $this->paramarray[] = $value;
            }
            $this->sqlstring = sprintf("insert into `%s` (%s) values (%s) ",$tablename,  implode(',', $keys),  implode(',', $transferparam));
        }
        
        return $this;
    }
    
    /**
     * Start a delete sentence
     * @param type $tablename
     * @return \DBDriver
     */
    public function delete($tablename){
        $this->clear();
        $this->sqlstring = "delete from ".$tablename;
        
        return $this;
        
    }
    
    /**
     * Add 'and where' to the sql sentence
     * @param type $where
     * @param type $symbo
     * @return \DBDriver
     */
    public function where($where,$symbo = "="){
        $index = 0;
        if(!$this->whereflag){
            $this->whereflag = true;
            $this->sqlstring .= " where ";
        }else{
            $this->sqlstring .= "and";
        }
        
        foreach ($where as $key => $value) {
            $this->paramarray[] = $value;
            $this->sqlstring .= " ".$key." ".$symbo." ? ";
            $index++;
            
            if($index < count($where)){
                $this->sqlstring .= "and";
            }
            
        }
        
        return $this;
        
    }
    
    /**
     * Add 'or where' to the sql sentence
     * @param type $where
     * @param type $symbo
     * @return \DBDriver
     */
    public function orwhere($where,$symbo = "="){
        $index = 0;
        if(!$this->whereflag){
            $this->whereflag = true;
            $this->sqlstring .= " where ";
        }else{
            $this->sqlstring .= " or ";
        }
        
        foreach ($where as $key => $value) {
            $this->paramarray[] = $value;
            $this->sqlstring .= " ".$key." ".$symbo." ? ";
            $index++;
            
            if($index < count($where)){
                $this->sqlstring .= "or";
            }
            
        }
        
        return $this;
    }
    
    private function query(){
        
        return $this->db->query($this->sqlstring, $this->paramarray);
    }
    /**
     * Query the sql sentence and return first result
     * @return type
     */
    public function rowResult(){
        $result = $this->query();
        
        return empty($result)?$result:$result[0];
    }
    
    /**
     * Query the sql sentence and return result
     * @return type
     */
    public function result(){
        
        return $this->query();
    }
    
    /**
     * execute the sql sentence and return affect row num
     * @return type
     */
    public function rowCount(){
        
        return $this->db->rowCount($this->sqlstring, $this->paramarray);
    }
    
    /**
     * execute the sql sentence
     */
    public function exec(){
        
        return $this->db->exec($this->sqlstring, $this->paramarray);
    }


    /**
     * print the sql sentence
     */
    public function printSQLandParam(){
        echo $this->sqlstring."<br>";
        var_dump($this->paramarray);
        
    }
    
    /**
     * clear sql sentence
     */
    public function clear(){
        $this->sqlstring = "";
        $this->paramarray = array();
        $this->whereflag = false;
    }
    
}
