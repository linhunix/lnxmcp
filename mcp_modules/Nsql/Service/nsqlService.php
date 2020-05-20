<?php

/**
 * LinHUniX Web Application Framework
 *
 * @author Andrea Morello <lnxmcp@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 */

namespace LinHUniX\Nsql\Service;

use LinHUniX\Mcp\Model\mcpServiceModelClass;

class nsqlService extends mcpServiceModelClass {


    private $dbtype;
    private $dbtable;
    private $tmptable;
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
        $this->tmptable=$this->dbtable;
        $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "vendor"=>"LinHUniX",
                "name"=>$this->dbtype."_Init"
            ),
            array("table"=>$this->dbtable)
        );        
    }

    /***
     * function doc_tableInit
     * [T]= doc
     * [E]= tableInit
     */
    public function doc_tableInit(){
        if (!isset($this->argIn["table"])){
            $this->argIn["table"]=$this->dbtable;
        }
        $this->tmptable=$this->argIn["table"];
        $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "vendor"=>"LinHUniX",
                "name"=>$this->dbtype."_Init"
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
       $this->argIn['doc_id']=intval($this->argIn['doc_id']);
       if (($this->argIn["doc_id"]==0) or($this->argIn["doc_id"]=="")){
           $this->argIn['doc_id']=intval(date('U'));
       }
       if (!isset($this->argIn["doc_extra"])){
           $this->argIn["doc_extra"]=array();
       }
       if (!is_array($this->argIn["doc_extra"])){
           $tmpedata=$this->argIn["doc_extra"];
           $this->argIn["doc_extra"]=array($tmpedata);
       }
       $tmpedata=$this->argIn["doc_extra"];
       try{
           $this->argIn["doc_extra"]= json_encode($tmpedata, JSON_PRETTY_PRINT);
       }catch(\Exception $e) {
           $this->warning('doc_init>>doc_extra:Err:'.$e->get_message());
           return false;
       }
       if (!isset($this->argIn["table"])){
           $this->argIn["table"]=$this->dbtable;
       }
       ///////////////////// verified if this id is already present
       $nid=false;
       while ($nid==false){
           lnxmcp()->debugVar("Nsql", "doc_init", 'Check '.$this->argIn['doc_id'] );
           $res= $this->callCmd(
               array(
                   "type"=>"queryJson",
                   "module"=>"Nsql",
                   "vendor"=>"LinHUniX",
                   "name"=>$this->dbtype."_DocChkId"
               ),
               $this->argIn
           ); 
           lnxmcp()->debugVar("Nsql", "doc_init", 'Check '.$this->argIn['doc_id'].'='.print_r($res,1) );
           if (!isset($res['doc'])){
               $nid=true;
               continue;
           }
           $this->argIn['doc_id']=intval($this->argIn['doc_id']+1);
       }
       ////  Store the new id
       $this->tmptable=$this->argIn["table"];
       $this->callCmd(
           array(
               "type"=>"queryJson",
               "module"=>"Nsql",
               "vendor"=>"LinHUniX",
               "name"=>$this->dbtype."_DocInit"
           ),
           $this->argIn
       ); 
       ////  get the new id
       $res= $this->callCmd(
           array(
               "type"=>"queryJson",
               "module"=>"Nsql",
               "vendor"=>"LinHUniX",
               "name"=>$this->dbtype."_DocGetId"
           ),
           $this->argIn
       ); 
       if (isset($res['doc'])){
           $this->getMcp()->setCommon($this->argIn["table"]."_doc_id",$res['doc']);
       }
       lnxmcp()->debugVar("Nsql","doc_init",$res);
       return $res;
    }
    /***
     * function doc_getDocByName(){
     * [T]= doc
     * [E]= getDocByName
     */
     public function doc_getDocByName(){
         $this->debug("doc_getDocByName");
        if (!isset($this->argIn["doc_name"])){
            return false;
        }
        if ($this->argIn["doc_name"]==""){
            return false;
        }
        if (!isset($this->argIn["table"])){
            $this->argIn["table"]=$this->dbtable;
        }
        ////  get the new id
        $res= $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "vendor"=>"LinHUniX",
                "name"=>$this->dbtype."_DocGetId"
            ),
            $this->argIn
        ); 
        if (isset($res['doc'])){
            $this->getMcp()->setCommon($this->argIn["table"]."_doc_id",$res['doc']);
        }
        lnxmcp()->debugVar("Nsql","doc_getDocByName",$res);
        return $res;
     }
         /***
     * function doc_initByName(){
     * [T]= doc
     * [E]= initByName
     */
    public function doc_initByName(){
        $this->debug("doc_initByName");
        if (!isset($this->argIn["doc_name"])){
            return false;
        }
        if ($this->argIn["doc_name"]==""){
            return false;
        }
        if (!isset($this->argIn["table"])){
            $this->argIn["table"]=$this->dbtable;
        }
         ////  get the new id
         $res= $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "vendor"=>"LinHUniX",
                "name"=>$this->dbtype."_DocGetId"
            ),
            $this->argIn
        ); 
        if (isset($res['doc'])){
            $this->getMcp()->setCommon($this->argIn["table"]."_doc_id",$res['doc']);
            lnxmcp()->debugVar("Nsql","doc_initByName",$res);
            return $res;
        }
        return $this->doc_init();
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
        $this->tmptable=$this->argIn["table"];
        $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "vendor"=>"LinHUniX",
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
        $this->tmptable=$this->argIn["table"];
        $res= $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "vendor"=>"LinHUniX",
                "name"=>$this->dbtype."_DocGetDocs"
            ),
            $this->argIn
        ); 
        lnxmcp()->debugVar("Nsql","doc_list",$res);
        $this->getMcp()->setCommon($this->argIn["table"]."_list",$res);
        return $res;
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
        $mode="DocSetVal";
        if ($this->argIn["doc_val"]=="."){
            $mode='DocDelVal';
        }
        if (!isset($this->argIn["doc_extra"])){
            $this->argIn["doc_extra"]=array();
        }
        if (!is_array($this->argIn["doc_extra"])){
            $tmpedata=$this->argIn["doc_extra"];
            $this->argIn["doc_extra"]=array($tmpedata);
        }
        $tmpedata=$this->argIn["doc_extra"];
        try{
            $this->argIn["doc_extra"]= json_encode($tmpedata, JSON_PRETTY_PRINT);
        }catch(\Exception $e) {
            $this->warning('doc_setval>>doc_extra:Err:'.$e->get_message());
            return false;
        }
        if (!isset($this->argIn["table"])){
            $this->argIn["table"]=$this->dbtable;
        }
        $this->tmptable=$this->argIn["table"];
        lnxmcp()->debugVar("Nsql", "doc_setval", 'Check '.$this->argIn['doc_id'] );
        $res= $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "vendor"=>"LinHUniX",
                "name"=>$this->dbtype."_DocChkId"
            ),
            $this->argIn
        ); 
        lnxmcp()->debugVar("Nsql", "doc_setval", 'Check '.$this->argIn['doc_id'].'='.print_r($res,1) );
        if (!isset($res['doc'])){
            return  false;
        }
        $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "vendor"=>"LinHUniX",
                "name"=>$this->dbtype."_".$mode
            ),
            $this->argIn
        ); 
        return true;
     }
     /***
     * function doc_setxval(){
     * [T]= doc
     * [E]= setxval
     */
    public function doc_setxval(){
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
        $mode="DocSetVal";
        if ($this->argIn["doc_val"]=="."){
            $mode='DocDelVal';
        }
        if (!isset($this->argIn["doc_extra"])){
            $this->argIn["doc_extra"]=array();
        }
        if (!is_array($this->argIn["doc_extra"])){
            $tmpedata=$this->argIn["doc_extra"];
            $this->argIn["doc_extra"]=array($tmpedata);
        }
        $tmpedata=$this->argIn["doc_extra"];
        try{
            $this->argIn["doc_extra"]= json_encode($tmpedata, JSON_PRETTY_PRINT);
        }catch(\Exception $e) {
            $this->warning('doc_setval>>doc_extra:Err:'.$e->get_message());
            return false;
        }
        if (!isset($this->argIn["table"])){
            $this->argIn["table"]=$this->dbtable;
        }
        $this->tmptable=$this->argIn["table"];
        $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "vendor"=>"LinHUniX",
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
        $this->tmptable=$this->argIn["table"];
        $res=$this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "vendor"=>"LinHUniX",
                "name"=>$this->dbtype."_DocGetVal"
            ),
            $this->argIn
        ); 
        $this->getMcp()->setCommon($this->argIn["table"]."_load",array($res));
        lnxmcp()->debugVar("Nsql","doc_getval",$res);
        if (isset($res["extra"])){
            $tmpedata=$res["extra"];
            try {
                $res["extra"]=json_decode($tmpedata, true);
            }catch(\Exception $e ){
                $this->warning('doc_getval>>extra:Err:'.$e->get_message());
            }
        }
        return $res;     
    }
    /***
     * function doc_getdoc
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
        $this->tmptable=$this->argIn["table"];
        $res= $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "vendor"=>"LinHUniX",
                "name"=>$this->dbtype."_DocGetAll"
            ),
            $this->argIn
        ); 
        $this->getMcp()->setCommon($this->argIn["table"]."_load",$res);
        lnxmcp()->debugVar("Nsql","doc_load",$res);
        return $res;
     }
    /***
     * function doc_finddoc
     * [T]= doc
     * [E]= finddoc
     */
    public function doc_finddoc(){
        lnxmcp()->debugVar("Nsql","doc_srcdata",$this->argIn);
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
        $qrytype="_DocFind";
        if (isset($this->argIn["doc_srcopt"])){
            if ($this->argIn["doc_srcopt"]!=""){
                $qrytype="_DocSrcOp";
            }
        }
        if ($this->argIn["doc_idx"]!=""){
            $qrytype.="_Extra";
        }
        if (!isset($this->argIn["table"])){
            $this->argIn["table"]=$this->dbtable;
        }
        $this->tmptable=$this->argIn["table"];
        $res= $this->callCmd(
            array(
                "type"=>"queryJson",
                "module"=>"Nsql",
                "vendor"=>"LinHUniX",
                "name"=>$this->dbtype.$qrytype
            ),
            $this->argIn
        ); 
        $this->getMcp()->setCommon($this->argIn["table"]."_list",$res);
        lnxmcp()->debugVar("Nsql","doc_list",$res);
        return $res;
     }
    /***
     * function doc_status
     * [T]= doc
     * [E]= status
     */
    public function doc_status(){
        return array(
            "dbtype"=>$this->dbtype,
            "dbtable"=>$this->tmptable
        );
    }
}
