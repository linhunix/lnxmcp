<?php
 $table=null;
 if (isset($scopeIn["table"])){
     $table=$scopeIn["table"];
 }
 $resn=lnxmcpNsql("list",array(),$table);
 if (isset($resn['doc_list'])) {
    $data=$resn['doc_list'];
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