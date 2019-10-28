<?php
 $table=null;
 if (isset($scopeIn["table"])){
     $table=$scopeIn["table"];
 }
$scopeNIn=array(
    "doc_var"=>$scopeIn['doc_name'],
    "doc_val"=>$scopeIn['doc_find'],
    "doc_idx"=>$scopeIn['doc_idx']
);
if (isset($scopeIn['doc_srcopt'])){
    $scopeNIn['doc_srcopt']=$scopeIn['doc_srcopt'];
}
$resn=lnxmcpNsql("finddoc",$scopeNIn,$table);
 if (isset($resn['doc_finddoc'])) {
    $data=$resn['doc_finddoc'];
 }

if (!isset($scopeIn["blockIn"])) {
     $scopeIn["blockIn"]=file_get_contents(__DIR__.'/../ViewAdm/listdoc.tpl');
 }
 foreach ($data as $row) {
     $block=$scopeIn["blockIn"];
     $block=str_replace("{{doc_id}}",$row["doc"],$block);
     $block=str_replace("{{doc_name}}",$row["value"],$block);
     $block=str_replace("{{doc_update}}",$row["date"],$block);
     echo $block;
 }
?>
<hr>
<pre>