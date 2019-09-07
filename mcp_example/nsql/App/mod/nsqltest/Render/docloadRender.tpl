<?php
 lnxmcp()->RemCommon();
 $data=lnxmcp()->getCommon("lnxnsqldata_load");
 foreach ($data as $row) {
     $block=$scopeIn["blockIn"];
     $block=str_replace("{{doc_id}}",$row["doc"],$block);
     $block=str_replace("{{doc_name}}",$row["name"],$block);
     $block=str_replace("{{doc_value}}",$row["value"],$block);
     $block=str_replace("{{doc_update}}",$row["date"],$block);
     echo $block;
 }
?>