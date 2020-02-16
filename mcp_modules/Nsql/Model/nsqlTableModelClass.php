<?php
/**
 * Created by PhpStorm.
 * User: linhunix
 * Date: 9/4/2018
 * Time: 10:11 AM
 */
namespace LinHUniX\Nsql\Model;

use LinHUniX\Mcp\masterControlProgram;

class nsqlFeaturesFilter {
    private $table='mytablename';
    private $folder='myfoldername';
    private $classdesc;
    /**
     * reload evere case use this class
     */
    protected function initparams() {
        $this->classdesc='Class '.__CLASS__.'->'.__METHOD__;
    }

    /**
     * Do not need to be load 
     * initialize the class and debug and table if need 
     */
    public function __construct(){
        lnxmcpNsql("tableInit",array(),$this->table);
        $this->initparams();
    }
    /**
     * upload image if are necessare on specified folder 
     */
    public function uploadimage($id,$fieldname,$others=array()){
        if (!is_array($other)){
    	    $other=array();
        }
        $other['category']=$this->folder;
        $other['idx']=$id;
        $other['fileconvert']='[idx]_[basename]';
        return lnxmcpUpload($other);
    }
    /**
     * return the list of doc are presente as array
     * @return array
     */
        public function list(){
        $resn=lnxmcpNsql("list",array(),$this->table);
        if (isset($resn['doc_list'])) {
           return $resn['doc_list'];
        }
        lnxmcp()->warning($this->classdesc.': data is null');
        return null;
    }
    /**
     * load a specific document with if need a list of the elemente is required
     */
    public function load($name,$list=null){
        if (empty($name)){
            lnxmcp()->warning($this->classdesc.': doc name is null');
            return false;
        }
        $scopeNIn=array(
            "doc_name"=>$name
        );
        $resn=lnxmcpNsql("getDocByName",$scopeNIn,$this->table); 
        if (!isset($resn['doc_getDocByName']['doc'])) {
            lnxmcp()->warning($this->classdesc.': data is null');
            return null;
        }
        $doc_id=$resn['doc_getDocByName']['doc'];
        if (!is_array($list)){
            return $doc_id;
        }
        $data=array("doc_id"=>$doc_id,"doc_name"=>$name);
        foreach($list as $var){
            $scopeNIn=array(
                "doc_id"=>$data['doc_id'],
                "doc_var"=>$var
            );
            $resn=lnxmcpNsql("getval",$scopeNIn,$this->table); 
            if (isset($resn['doc_getval']['value'])){
                $data[$var]=$resn['doc_getval']['value'];
            }
        }
        return $data;
    }
    /**
     * load the doc with all element 
     */
    public function loadall($docid){
        $scopeNIn=array("doc_id"=>$docid);
        $resn=lnxmcpNsql("getdoc",$scopeNIn,$this->table);
        if (is_array($resn)){
            if (isset($resn['doc_getdoc'])) {
                if (is_array($resn['doc_getdoc'])){
                    $reorgdoc=array();
                    foreach($resn['doc_getdoc'] as $cffk=>$cffv){
                        $reorgdoc[$cffv['name']]=$cffv;
                    }
                    $resn['doc_getdoc']=$reorgdoc;
                }            
                return $resn['doc_getdoc'];
            }    
        } 
        return null;
    }
    /**
     * create a new doc by specific name and save data 
     */
    public function add($docname,$data){
        if (empty($docname)){
            lnxmcp()->warning($this->classdesc.': name is null');
            return false;
        }
        if (!is_array($data)){
            lnxmcp()->warning($this->classdesc.': data is not array');
            return false;
        }
        $doc_id=0;
        $resn=lnxmcpNsql("initByName",array('doc_name'=>$docname),$this->table); 
        if (isset($resn['doc_initByName']['doc'])) {
            $doc_id=$resn['doc_initByName']['doc'];
        }
        foreach ($data as $var=>$val) {
            $scopeNIn=array(
                "doc_id"=>$doc_id,
                "doc_var"=>$var,
                "doc_val"=>$val
            );
            $resn=lnxmcpNsql("setval",$scopeNIn,$this->table); 
        }

    }
    /**
     * update a doc with data 
     */
    public function update($doc_id,$doc_name,$data,$force=false){
        if (empty($doc_id)){
            lnxmcp()->warning($this->classdesc.': docid is null');
            return false;
        }
        if (empty($doc_name)){
            lnxmcp()->warning($this->classdesc.': docname is null');
            return false;
        }

        if (!is_array($data)){
            lnxmcp()->warning($this->classdesc.': data is not array');
            return false;
        }
        $doc_id=intval($doc_id);
        if ($doc_id==0){
            lnxmcp()->warning($this->classdesc.': docid is not valid');
            return false;
        }
        if (isset($data['doc']) and $doc_name!=$data['doc']){
            $scopeNIn=array(
                "doc_id"=>$doc_id,
                "doc_var"=>'doc',
                "doc_val"=>$data['doc']
            );
            if (lnxmcpNsql("setxval",$scopeNIn,$this->table)){
                $doc_name=$data['doc'];
                unset($data['doc']);
            } 
                        
        }
        foreach ($data as $var=>$val) {
            $scopeNIn=array(
                "doc_id"=>$doc_id,
                "doc_var"=>$var,
                "doc_val"=>$val
            );
            $savecmd='setval';
            if ($force==true){
                $savecmd='setxval';
            }
            $resn=lnxmcpNsql($savecmd,$scopeNIn,$this->table); 
        }
    }
    /**
     * delete doc 
     */
    public function delete($doc_id){
        if (empty($doc_id)){
            lnxmcp()->warning($this->classdesc.': docid is null');
            return false;
        }
        $doc_id=intval($doc_id);
        if ($doc_id==0){
            lnxmcp()->warning($this->classdesc.': docid is not valid');
            return false;
        }
	$scopeNIn=array(
	    "doc_id"=>$doc_id,
	);
	return lnxmcpNsql("delete",$scopeNIn,$this->table); 
    }
}