<?php

/**
 * LinHUniX Web Application Framework
 *
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 */

namespace LinHUniX\Nsql\Service;

use LinHUniX\Mcp\Model\mcpServiceModelClass;

class nsqlService extends mcpServiceModelClass {


    private $dbtype;
    private $dbtable;
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
        $this->dbtype=$this->getCfg("app.nsql.dbtype");
        if ($this->dbtype==''){
            $this->dbtype="sqllite";
        }
        $this->dbtable=$this->getCfg("app.nsql.dbtable");
        if ($this->dbtable==''){
            $this->dbtable="lnxnsqldata";
        }
        $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "name"=>$this->dbtype."_Init"
            ),
            array("table"=>$this->dbtable)
        );        
    }

    /***
     * function table_Init(){
     * [T]= table
     * [E]= init
     */
    public function table_Init(){
        if (!isset($this->argIn["doc_name"])){
            return false;
        }
        if ($this->argIn["doc_name"]==""){
            return false;
        }
        if (!isset($this->argIn["doc_id"])){
            $this->argIn["doc_id"]=date('U');
        }
        if ($this->argIn["doc_id"]==""){
            $this->argIn["doc_id"]=date('U');
        }
        if (!isset($this->argIn["table"])){
            $this->argIn["table"]=$this->dbtable;
        }
        $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "name"=>$this->dbtype."_DocInit"
            ),
            $this->argIn
        ); 
        return $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "name"=>$this->dbtype."_DocId"
            ),
            $this->argIn
        ); 
     }

    /***
     * function doc_Init(){
     * [T]= doc
     * [E]= init
     */
     public function doc_init(){
         $this->debug("doc_init");
        if (!isset($this->argIn["doc_name"])){
            return false;
        }
        if ($this->argIn["doc_name"]==""){
            return false;
        }
        if (!isset($this->argIn["doc_id"])){
            $this->argIn["doc_id"]=date('U');
        }
        if ($this->argIn["doc_id"]==""){
            $this->argIn["doc_id"]=date('U');
        }
        if (!isset($this->argIn["table"])){
            $this->argIn["table"]=$this->dbtable;
        }
        $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "name"=>$this->dbtype."_DocInit"
            ),
            $this->argIn
        ); 
        $res= $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "name"=>$this->dbtype."_DocGetId"
            ),
            $this->argIn
        ); 
        if (isset($res['doc'])){
            $this->getMcp()->setCommon("doc_id",$res['doc']);
        }
        lnxmcp()->debugVar("Nsql","doc_init",$res);
        return $res;
     }
    /***
     * function doc_delete(){
     * [T]= doc
     * [E]= delete
     */
    public function doc_delete(){
        if (!isset($this->argIn["doc_id"])){
            return false;
        }
        if ($this->argIn["doc_id"]==""){
            return false;
        }
        if (!isset($this->argIn["table"])){
            $this->argIn["table"]=$this->dbtable;
        }
        $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "name"=>$this->dbtype."_DocDelete"
            ),
            $this->argIn
        ); 
        return true;
     }
    /***
     * function doc_list(){
     * [T]= doc
     * [E]= list
     */
    public function doc_list(){
        if (!isset($this->argIn["table"])){
            $this->argIn["table"]=$this->dbtable;
        }
        $res= $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "name"=>$this->dbtype."_DocGetDocs"
            ),
            $this->argIn
        ); 
        $this->getMcp()->setCommon($this->argIn["table"]."_list",$res);
        lnxmcp()->debugVar("Nsql","doc_list",$res);
     }
     /***
     * function doc_setval(){
     * [T]= doc
     * [E]= setval
     */
    public function doc_setval(){
        if (!isset($this->argIn["doc_id"])){
            return false;
        }
        if ($this->argIn["doc_id"]==""){
            return false;
        }
        if (!isset($this->argIn["doc_var"])){
            return false;
        }
        if ($this->argIn["doc_var"]==""){
            return false;
        }
        if (!isset($this->argIn["doc_val"])){
            return false;
        }
        if ($this->argIn["doc_val"]==""){
            return false;
        }
        $mode="SetVal";
        if ($this->argIn["doc_val"]=="."){
            $mode='DelVal';
        }
        if (!isset($this->argIn["table"])){
            $this->argIn["table"]=$this->dbtable;
        }
        $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "name"=>$this->dbtype."_".$mode
            ),
            $this->argIn
        ); 
        return true;
     }
    /***
     * function doc_getval(){
     * [T]= doc
     * [E]= getval
     */
    public function doc_getval(){
        if (!isset($this->argIn["doc_id"])){
            return false;
        }
        if ($this->argIn["doc_id"]==""){
            return false;
        }
        if (!isset($this->argIn["doc_var"])){
            return false;
        }
        if ($this->argIn["doc_var"]==""){
            return false;
        }
        if (!isset($this->argIn["table"])){
            $this->argIn["table"]=$this->dbtable;
        }
        return $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "name"=>$this->dbtype."_GetVal"
            ),
            $this->argIn
        ); 
        return true;
     }
    /***
     * function doc_getdoc(){
     * [T]= doc
     * [E]= getdoc
     */
    public function doc_getdoc(){
        if (!isset($this->argIn["doc_id"])){
            return false;
        }
        if ($this->argIn["doc_id"]==""){
            return false;
        }
        if (!isset($this->argIn["table"])){
            $this->argIn["table"]=$this->dbtable;
        }
        $res= $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "name"=>$this->dbtype."_DocGetAll"
            ),
            $this->argIn
        ); 
        $this->getMcp()->setCommon($this->argIn["table"]."_load",$res);
        lnxmcp()->debugVar("Nsql","doc_load",$res);
        return $res;
     }
    /***
     * function doc_getdoc(){
     * [T]= doc
     * [E]= finddoc
     */
    public function doc_finddoc(){
        if (!isset($this->argIn["doc_var"])){
            return false;
        }
        if ($this->argIn["doc_var"]==""){
            return false;
        }
        if (!isset($this->argIn["doc_val"])){
            return false;
        }
        if ($this->argIn["doc_val"]==""){
            return false;
        }
        if (!isset($this->argIn["table"])){
            $this->argIn["table"]=$this->dbtable;
        }
        $res= $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "name"=>$this->dbtype."_DocFind"
            ),
            $this->argIn
        ); 
        $this->getMcp()->setCommon($this->argIn["table"]."_list",$res);
        lnxmcp()->debugVar("Nsql","doc_list",$res);
        return $res;
     }
     
}
