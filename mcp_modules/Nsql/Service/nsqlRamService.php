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
    ////////////////////////////////////////////////////////////////////////////
    /// SERVICE OPERATOR
    ////////////////////////////////////////////////////////////////////////////
    

}