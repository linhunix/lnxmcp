<?php

/**
 * LinHUniX Web Application Framework
 * Log Sql Service
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 */

namespace LinHUniX\Lsql\Service;

use LinHUniX\Mcp\Model\mcpServiceModelClass;

class lsqlService extends mcpServiceModelClass {
    private $dbtype;
    private $dbtable;
    private $tmptable;

    const field_unixtime='unixtime';
    const field_app='app';
    const field_usr='user';
    const field_level='level';
    const field_log='logdata';
    /**
     *  function moduleInit() 
     */
    protected function moduleInit(){
        $this->spacename=__NAMESPACE__;
        $this->classname=__CLASS__;
    }

    /**
     * standard 1 shot user
     */
    protected function moduleSingleTon() {
        $this->dbtype=$this->getCfg("app.lsql.dbtype");
        if ($this->dbtype==''){
            $this->dbtype="mysql";
        }
        $this->dbtable=$this->getCfg("app.lsql.dbtable");
        if ($this->dbtable==''){
            $this->dbtable="lnxlogdata";
        }
        $this->tmptable=$this->dbtable;
        $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Lsql",
                "vendor"=>"LinHUniX",
                "name"=>$this->dbtype."_Init"
            ),
            array("table"=>$this->dbtable)
        );        
    }

    /***
     * function log_init()
     * [T]= log
     * [E]= init
     */
    public function log_init(){
        if (!isset($this->argIn["table"])){
            $this->argIn["table"]=$this->dbtable;
        }
        $this->tmptable=$this->argIn["table"];
        $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Lsql",
                "vendor"=>"LinHUniX",
                "name"=>$this->dbtype."_Init"
            ),
            $this->argIn
        ); 
     }

    /***
     * function log_write()
     * [T]= log
     * [E]= write
     */
    public function log_write(){
        if (! $this->checkArgIn(self::field_app)){
            $this->warning(self::field_app.' not valid!!!');
            return false;
        }
        if (! $this->checkArgIn(self::field_usr)){
            $this->warning(self::field_usr.' not valid!!!');
            return false;
        }
        if (! $this->checkArgIn(self::field_log,'a')){
            $this->warning(self::field_log.' not valid!!!');
            return false;
        }
        if (!isset($this->argIn["table"])){
            $this->argIn["table"]=$this->dbtable;
        }
        if (! $this->checkArgIn(self::field_level,'n')){
            $this->argIn[self::field_level]=50;
        }
        $this->argIn[self::field_unixtime]=date('U');
        try{
            $tmp= json_encode($this->argIn[self::field_log], JSON_PRETTY_PRINT);
            $this->argIn[self::field_log]=$tmp;
        }catch(\Exception $e) {
            $this->warning('log_write>>'.self::field_log.':Err:'.$e->get_message());
            return false;
        }
       ///////////////////// verified if this id is already present
        lnxmcp()->debugVar("Lsql", "log_write", $this->argIn );
        $res= $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Lsql",
                "vendor"=>"LinHUniX",
                "name"=>$this->dbtype."_Write"
            ),
            $this->argIn
        ); 
       return $res;
    }

    /***
     * function log_read()
     * [T]= log
     * [E]= read
     */
     public function log_read(){
        $query='ReadAll';
        if (! $this->checkArgIn(self::field_app)){
            $this->warning(self::field_app.' not valid!!!');
            return false;
        }
        if (!isset($this->argIn["table"])){
            $this->argIn["table"]=$this->dbtable;
        }
        if (! $this->checkArgIn(self::field_level,'n')){
            $this->argIn[self::field_level]=50;
        }
        if (! $this->checkArgIn('from','s')){
            $this->argIn['from']=0;
        }else{
            try{
                $time = strtotime($this->argIn['from']);
                $this->argIn['from']=date('U',$time);
            }catch(Exception $e){
                $this->argIn['from']=0;
            }
        }
        if (! $this->checkArgIn('to','s')){
            $this->argIn['to']=999999999999999999;
        }else{
            try{
                $time = strtotime($this->argIn['to']);
                $this->argIn['to']=date('U',$time);
            }catch(Exception $e){
                $this->argIn['to']=999999999999999999;
            }
        }
        if ($this->checkArgIn(self::field_usr,'s')){
            $query='ReadUser';
        }
        lnxmcp()->debugVar("Lsql", "log_read", $this->argIn );
        ////  get the new id
        $res= $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Lsql",
                "vendor"=>"LinHUniX",
                "name"=>$this->dbtype."_".$query
            ),
            $this->argIn
        ); 
        if (is_array($res)){
            $result=array();
            foreach($res as $k=>$v){
                $result[$k]=$v;
                try{
                    $vx=json_decode($v[self::field_log],true);
                    if (is_array($vx)){
                        foreach($vx as $xk=>$xv){
                            $result[$k]['log_'.$xk]=$xv;
                        }
                    }
                }catch( Exception $e) {
                    $result[$k]['log_error']=$e->getMessage();
                }
            }
            $res=$result;
        }
        return $res;
     }
    /***
     * function log_archive()
     * [T]= log
     * [E]= archive
     */
    public function log_archive(){
        if (! $this->checkArgIn(self::field_app)){
            $this->warning(self::field_app.' not valid!!!');
            return false;
        }
        if (! $this->checkArgIn('from','s')){
            $this->warning('from not valid!!!');
            return false;
        }
        if (! $this->checkArgIn('to','s')){
            $this->warning('to not valid!!!');
            return false;
        }
        if (!isset($this->argIn["table"])){
            $this->argIn["table"]=$this->dbtable;
        }
        $this->argIn["tableA"]=$this->argIn["table"].'_'.date('U');
        $res= $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Lsql",
                "vendor"=>"LinHUniX",
                "name"=>$this->dbtype."_ArchiveStep1"
            ),
            $this->argIn
        ); 
        if ($res!=false){
            $res= $this->callCmd(
                array(
                    "type"=>"queryJson",
                    "module"=>"Lsql",
                    "vendor"=>"LinHUniX",
                    "name"=>$this->dbtype."_ArchiveStep2"
                ),
                $this->argIn
            ); 
        }
        return $res;
    }


}
