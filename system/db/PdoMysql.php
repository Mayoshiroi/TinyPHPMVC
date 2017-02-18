<?php defined('ROOTPATH') OR exit();

class PdoMysql {
    /**
     * PDO connection class
     * 为了安全 所有查询都要使用mysql转义
     */
    
    private $config;
    private $pdodb;
    
    public function __construct($config,$islongconnection = false) {
        
        $this->config = $config;
        
        //connect db server
        $islongconnection?$this->pdodb = new PDO("mysql:host=".$config["address"].';dbname='.$config['name'],$config['user'],$config['passwd'],"array(PDO::ATTR_PERSISTENT => true)"):
            $this->pdodb = new PDO("mysql:host=".$config["address"].';dbname='.$config['name'],$config['user'],$config['passwd']);
        $this->pdodb->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        
    }
    
    /**
     *  exec sql sentence 
     * @param type $sqlstr
     * @param type $paramarray
     */
    public function exec($sqlstr,$paramarray){
        
        $st = $this->pdodb->prepare($sqlstr);
        for($i=1;$i<=count($paramarray);$i++){
            $st->bindParam($i, $paramarray[$i-1]);
        }
        $st->execute();
        
        return $st;
    }


    /**
     * exec sql sentence and return query result
     * @param type $sqlstr sql query sentence
     * @param type $paramarray bind param array
     * @return type result array
     */
    public function query($sqlstr,$paramarray){
        
        $st = $this->exec($sqlstr, $paramarray);
                
        return $st->fetchAll();
    }
    
    /**
     * exec sql sentence and return row count
     * @param type $sqlstr sql exec sentence
     * @param type $paramarray bind param array
     * @return type row count
     */
    public function rowCount($sqlstr,$paramarray){
        
        $st = $this->exec($sqlstr, $paramarray);
        
        return $st->rowCount();
    }

    /**
     * begin transaction
     */
    public function beginTransaction(){
        $this->pdodb->beginTransaction();
    }
    
    /**
     * commit transaction
     */
    public function commit(){
        $this->pdodb->beginTransaction();
    }
    
    /**
     * rollback transaction
     */
    public function rollback(){
        $this->pdodb->rollBack();
    }
}
