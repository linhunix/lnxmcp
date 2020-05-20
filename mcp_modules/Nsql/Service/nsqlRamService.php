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

class nsqlRamService extends mcpServiceModelClass {


    private $RamDB;
    /**
     *  function moduleInit() 
     */
    protected function moduleInit(){
        $this->spacename=__NAMESPACE__;
        $this->classname=__CLASS__;
        $this->RamDB=array();
    }

    /**
     * standard 1 shot user
     */
    protected function moduleSingleTon() {
        $dblist=$this->getCfg("app.nsql.RamTablesList");
        if (is_array($dblist)){
            foreach($dblist as $kdbl=>$vdbl){
                $this->loadTable($kdbl,$vdbl);
            }            
        }

    }
    ////////////////////////////////////////////////////////////////////////////
    /// FIELD OPERATOR
    ////////////////////////////////////////////////////////////////////////////
    
    public function setField($table,$doc,$field,$value){
        if (! is_array($this->RamDB[$table])){
            $this->RamDB[$table]=array();
        }
        if (! is_array($this->RamDB[$table][$field])){
            $this->RamDB[$table][$field]=array();
        }
        $this->RamDB[$table][$field][$doc]=$value;
        if ($value=='.'){
            unset($this->RamDB[$table][$field][$doc]);
        }
    }
    public function getField($table,$doc,$field){
        if (is_array($this->RamDB[$table][$field][$doc])){
           return $this->RamDB[$table][$field][$doc];
        }
        return null;
    }
    public function getFields($table,$field){
        if (is_array($this->RamDB[$table][$field])){
           return $this->RamDB[$table][$field];
        }
        return null;
    }
    public function isLikeField($table,$field,$search,$ids=null){
        $fields= getFields($table, $field);
        $idx=array();
        foreach($fields as $doc=>$value) {
            if (is_array($ids)){
                if (!in_array($doc,$idx)){
                    continue;
                }
            }
            if (stristr($value,$search)!=false){
                $idx[$doc]=$value;
            }    
        }
        return $idx;
    }
    public function isNotLikeField($table,$field,$search,$ids=null){
        $fields= getFields($table, $field);
        $idx=array();
        foreach($fields as $doc=>$value) {
            if (is_array($ids)){
                if (!in_array($doc,$idx)){
                    continue;
                }
            }
            if (!stristr($value,$search)!=false){
                $idx[$doc]=$value;
            }    
        }
        return $idx;
    }
    public function isEgualField($table,$field,$search,$ids=nul){
        $fields= getFields($table, $field);
        $idx=array();
        foreach($fields as $doc=>$value) {
            if (is_array($ids)){
                if (!in_array($doc,$idx)){
                    continue;
                }
            }
            if ($value ==$search){
                $idx[$doc]=$value;
            }    
        }
        return $idx;
    }
    public function isNotEgualField($table,$field,$search,$ids=nul){
        $fields= getFields($table, $field);
        $idx=array();
        foreach($fields as $doc=>$value) {
            if (is_array($ids)){
                if (!in_array($doc,$idx)){
                    continue;
                }
            }
            if ($value !=$search){
                $idx[$doc]=$value;
            }    
        }
        return $idx;
    }

    public function isUpperField($table,$field,$search,$ids=nul){
        $fields= getFields($table, $field);
        $idx=array();
        foreach($fields as $doc=>$value) {
            if (is_array($ids)){
                if (!in_array($doc,$idx)){
                    continue;
                }
            }
            if ($value < $search){
                $idx[$doc]=$value;
            }
        }
        return $idx;
    }
    public function isLowerField($table,$field,$search,$ids=nul){
        $fields= getFields($table, $field);
        $idx=array();
        foreach($fields as $doc=>$value) {
            if (is_array($ids)){
                if (!in_array($doc,$idx)){
                    continue;
                }
            }
            if ($value > $search){
                $idx[$doc]=$value;
            }
        }
        return $idx;
    }
    ////////////////////////////////////////////////////////////////////////////
    /// Doc OPERATOR
    ////////////////////////////////////////////////////////////////////////////
    public function setDoc($table,$rowarray,$docid,$update=false,$extra=array()){
        $this->setField($table,$docid,"doc",$docid);
        $this->setField($table,$docid,"extra",$extra);
        $this->setField($table,$docid,"update",$update);
        if (is_array($rowarray)){
            foreach ($rowarray as $sak=>$sav){
                $this->setField($table,$docid,$sak,$sav);
            }
        } else {
            $this->setField($table,$docid,"name",$rowarray);
        }
    }
    public function getDoc($table,$docid,$fieldarr=null){
        $row=array();
        $row["doc"]=$this->getField($table,$docid,"doc");
        $row["update"]=$this->getField($table,$docid,"update");
        $row["extra"]=$this->getField($table,$docid,"extra");
        if (!is_array($fieldarr)){
            $fieldarr=$this->getField($table,0,"fields");
        }
        if (!is_array($fieldarr)){
            $fieldarr=array("name");
        }
        foreach ($fieldarr as $sak){
            $row[$sak]=$this->getField($table,$docid,$sak);
        }
    }
    public function isDocUpdate($table,$docid){
        return $this->getField($table,$docid,"update");
    }
    public function delDoc($table,$docid){
        $fieldarr=$this->getField($table,0,"fields");
        foreach ($fieldarr as $sak){
            $this->setField($table,$docid,$sak,'.');
        }
        $this->setField($table,$docid,"doc",'.');
        $this->setField($table,$docid,"update",'.');
        $this->setField($table,$docid,"extra",'.');
    }
    public function addDocCol($table,$colname){
        $fieldarr=$this->getField($table,0,"fields");
        $fieldarr[]=$colname;
        $this->setField($table,0,"fields",$fieldarr);
        if (!isset($this->RamDB[$table][$colname])) {
            $this->RamDB[$table][$colname]=array();
        }
    }
    public function delDocCol($table,$colname){
        $fieldarr=$this->getField($table,0,"fields");
        foreach ($fieldarr as $k=>$sak){
            if ($sak==$colname){
                unset($fieldarr[$k]);
                if (isset($this->RamDB[$table][$colname])) {
                    unset($this->RamDB[$table][$colname]);
                }
            }
        }
        $this->setField($table,0,"fields",$fieldarr);
    }
    ////////////////////////////////////////////////////////////////////////////
    /// ROW OPERATOR
    ////////////////////////////////////////////////////////////////////////////
    public function generateTable($table,$dataarray,$update=false,$index='id',$extra=null){
        if (!is_array($dataarray)){
            return false;
        }
        $isrow0=true;
        $iscount=true;
        $idr=0;
        if ($index=='unixtime') {
            $idr=date('u');
        }
        foreach ($dataarray as $did=>$docval){
            if ($isrow0){
                $fields=array();
                foreach($docval as $field=>$val){
                    $fields[]=$filed;
                }
                $this->setField($table,0,"fields",$fields);
                $isrow0=false;
            }
            if (isset($docval[$index])){
                $idr=$docval[$index];
                $iscount=false;
            }
            if ($iscount){
                $idr++;
            }
            $extrares=array();
            if (is_callable($extra)){
                $extrares=$extra($docval);
            }elseif (is_array($extra)){
                $extrares=$extra;
            }
            $this->setDoc($table,$docval,$idr,$update,$extrares);
        }
    }
    public function loadTable($table,$tableCtl,$tableIn=array()){
        $res=$this->callCmd($tableCtl,$tableIn);
        $update=false;
        $index=null;
        $extra=null;
        if (isset($tableCtl['update'])){
            $update=$tableCtl['update'];
        }        
        if (isset($tableCtl['index'])){
            $index=$tableCtl['index'];
        }        
        if (isset($tableCtl['extra'])){
            $extra=$tableCtl['extra'];
        }        
        $this->generateTable($table,$res,$update,$index,$extra);
    }
    public function getTable($table,$fieldlist=null,$idx=null) {
        if ($idx==null){
            $idx=$this->getFields($table,"doc");
        }
        $rows=array();
        foreach($idx as $docid=>$docval){
            $rows[$docid]=$this->getDoc($table,$docval,$fieldlist);
        }
        return $rows;
    }
    public function isLiveTable($table){
        if (isset($this->RamDB[$table])){
            return true;
        }
        return false;
    }
    public function deleteTable($table){
        if (isset($this->RamDB[$table])){
            unset($this->RamDB[$table]);
            return true;
        }
        return false;
    }
    ////////////////////////////////////////////////////////////////////////////
    /// SERVICE OPERATOR (table)
    ////////////////////////////////////////////////////////////////////////////
    /**
     * table_in 
     * required arg table,data
     * optional update, index, extra
     * 
     * @return void
     */
    public function table_in(){
        if (! isset($this->argIn["table"])){
            return null;
        }
        if (! isset($this->argIn["data"])){
            return null;
        }
        $update=false;
        $index=null;
        $extra=null;
        if ( isset($this->argIn["update"])){
            $update=$this->argIn["update"];
        }
        if ( isset($this->argIn["index"])){
            $index=$this->argIn["index"];
        }
        if ( isset($this->argIn["extra"])){
            $extra=$this->argIn["extra"];
        }
        return $this->generateTable(
            $this->argIn["table"],
            $this->argIn["data"],
            $update,
            $index,
            $extra
        );
    }
    /**
     * table_out
     * required arg table
     * optional fieldlist, index
     */
    public function table_out(){
        if (! isset($this->argIn["table"])){
            return null;
        }
        $fieldlist=null;
        $index=null;
        if ( isset($this->argIn["fieldlist"])){
            $fieldlist=$this->argIn["fieldlist"];
        }
        if ( isset($this->argIn["index"])){
            $index=$this->argIn["index"];
        }
        return $this->getTable(
            $this->argIn["table"],
            $fieldlist,
            $index
        );
    }
    /**
     * table_up
     * required arg table
     * @return bool
     */
    public function table_ok(){
        if (! isset($this->argIn["table"])){
            return false;
        }
        return $this->isLiveTable($this->argIn["table"]);
    }
    /**
     * table_del
     * required arg table
     * @return bool
     */
    public function table_del(){
        if (! isset($this->argIn["table"])){
            return false;
        }
        return $this->deleteTable($this->argIn["table"]);
    }
    ////////////////////////////////////////////////////////////////////////////
    /// SERVICE OPERATOR (doc)
    ////////////////////////////////////////////////////////////////////////////
    /**
     * doc_in
     * required arg table,docid,data
     * optional update,extra
     * @return void
     */
    public function doc_in(){
        if (! isset($this->argIn["table"])){
            return false;
        }
        if (! isset($this->argIn["docid"])){
            return false;
        }
        if (! isset($this->argIn["data"])){
            return null;
        }
        $update=false;
        $extra=null;
        if ( isset($this->argIn["update"])){
            $update=$this->argIn["update"];
        }
        if ( isset($this->argIn["extra"])){
            $extra=$this->argIn["extra"];
        }
        $this->setDoc(
            $this->argIn["table"],
            $this->argIn["data"],
            $this->argIn["docid"],
            $update,
            $extra
        );
    }
    /**
     * doc_out
     * required arg table,docid
     * optional fieldlist
     * @return array
     */
    public function doc_out(){
        if (! isset($this->argIn["table"])){
            return false;
        }
        if (! isset($this->argIn["docid"])){
            return false;
        }
        $fieldlist=null;
        if ( isset($this->argIn["fieldlist"])){
            $fieldlist=$this->argIn["fieldlist"];
        }
        return $this->getDoc(
            $this->argIn["table"],
            $this->argIn["docid"],
            $fieldlist
        );
    }
        /**
     * doc_out
     * required arg table,docidx
     * optional fieldlist
     * @return array
     */
    public function doc_outlist(){
        if (! isset($this->argIn["table"])){
            return false;
        }
        if (! isset($this->argIn["docidx"])){
            return false;
        }
        if (! is_array($this->argIn["docidx"])) {
            return false;
        }
        $fieldlist=null;
        if ( isset($this->argIn["fieldlist"])){
            $fieldlist=$this->argIn["fieldlist"];
        }
        $docs=array();
        foreach ($this->argIn["docidx"] as $docid) {
            $docs[$docid]=$this->getDoc(
                $this->argIn["table"],
                $docid,
                $fieldlist
            );
        }       
        return $docs;
    }
    /**
     * doc_del
     * required arg table,docid
     * @return bool
     */
    public function doc_del(){
        if (! isset($this->argIn["table"])){
            return false;
        }
        if (! isset($this->argIn["docid"])){
            return false;
        }
        return $this->delDoc(
            $this->argIn["table"],
            $this->argIn["docid"]
        );
    }
        /**
     * doc_src
     * required arg table,docid,field,search,srctype
     * @return bool
     */
    public function doc_src(){
        if (! isset($this->argIn["table"])){
            return false;
        }
        if (! isset($this->argIn["docid"])){
            return false;
        }
        if (! isset($this->argIn["field"])){
            return false;
        }
        if (! isset($this->argIn["search"])){
            return false;
        }
        if (! isset($this->argIn["srctype"])){
            return false;
        }
        $idx=null;
        if ( isset($this->argIn["doclist"])){
            $idx=$this->argIn["doclist"];
        }
        switch ($this->argIn["srctype"]){
            case "like":
                $idx=$this->isLikeField(
                    $this->argIn["table"],
                    $this->argIn["field"],
                    $this->argIn["search"],
                    $idx
                );
                break;;
            case "not like":
                $idx=$this->isNotLikeField(
                    $this->argIn["table"],
                    $this->argIn["field"],
                    $this->argIn["search"],
                    $idx
                );
                break;;
            case "low":
                $idx=$this->isLowerField(
                    $this->argIn["table"],
                    $this->argIn["field"],
                    $this->argIn["search"],
                    $idx
                );
                break;;
            case "up":
                $idx=$this->isUpperField(
                    $this->argIn["table"],
                    $this->argIn["field"],
                    $this->argIn["search"],
                    $idx
                );
                break;;
            case "not":
                $idx=$this->isNotEgualField(
                    $this->argIn["table"],
                    $this->argIn["field"],
                    $this->argIn["search"],
                    $idx
                );
                break;;
            default:
                $idx=$this->isEgualField(
                    $this->argIn["table"],
                    $this->argIn["field"],
                    $this->argIn["search"],
                    $idx
                );
                break;;
        }
        return $idx;
    }
    /**
    * doc_srclst
    * required arg table,docid,field,search,srctype
    * optional arg fieldlist
    * @return bool
    */
    public function doc_srclst(){
        $this->argIn["docidx"]=$this->doc_src();
        $docs=array();
        $fieldlist=null;
        if ( isset($this->argIn["fieldlist"])){
            $fieldlist=$this->argIn["fieldlist"];
        }
        if (is_array($this->argIn["docidx"])) {
            foreach ($this->argIn["docidx"] as $docid) {
                $docs[$docid]=$this->getDoc(
                    $this->argIn["table"],
                    $docid,
                    $fieldlist
                );
            }   
        }    
        return $docs;
    }
}