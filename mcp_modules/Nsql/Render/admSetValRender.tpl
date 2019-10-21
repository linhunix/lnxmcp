<?php
 $table=null;
 if (isset($scopeIn["table"])){
     $table=$scopeIn["table"];
 }
$scopeNIn=array(
    "doc_id"=>$scopeIn['doc_id'],
    "doc_var"=>$scopeIn['doc_name'],
    "doc_val"=>$scopeIn['doc_value'],
);
$resn=lnxmcpNsql("setval",$scopeNIn,$table); 
$resn=lnxmcpNsql("getdoc",$scopeNIn,$table); 
$data=array();
if (isset($resn['doc_getdoc'])) {
    $data=$resn['doc_getdoc'];
}else{
    $data=lnxmcp()->getCommon("lnxnsqldata_load");
}
if (!isset($scopeIn["blockIn"])) {
     $scopeIn["blockIn"]=file_get_contents(__DIR__.'/../ViewAdm/loaddoc.tpl');
 }
 foreach ($data as $row) {
     $block=$scopeIn["blockIn"];
     $block=str_replace("{{doc_id}}",$row["doc"],$block);
     $block=str_replace("{{doc_name}}",$row["name"],$block);
     $block=str_replace("{{doc_value}}",$row["value"],$block);
     $block=str_replace("{{doc_update}}",$row["date"],$block);
     $block=str_replace("{{doc_extra}}",$row["extra"],$block);
     echo $block;
 }
?>
<hr>
<pre>