<?php
 lnxmcp()->RemCommon();
 $data=lnxmcp()->getCommon("lnxnsqldata_list");
 foreach ($data as $row) {
     $block=$scopeIn["blockIn"];
     $block=str_replace("{{doc_id}}",$row["doc"],$block);
     $block=str_replace("{{doc_name}}",$row["value"],$block);
     $block=str_replace("{{doc_update}}",$row["date"],$block);
     echo $block;
 }
?>