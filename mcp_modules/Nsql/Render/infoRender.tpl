<?php
$block=$scopeIn["blockIn"];
$res=lnxmcp()->runCommand(
            array(
                "type"=>"serviceCommonReturn",
                "module"=>"Nsql",
                "name"=>"nsql",
                "vendor"=>"LinHUniX"
            ),
            array(
                "T"=>"doc",
                "E"=>"status"
            )
); 
echo "\n<!--\n";
print_r($res);
echo "\n!-->\n";
if (!isset($res['doc_status'])){
    $res['doc_status']=array();
}
foreach($res['doc_status'] as $rk=>$rv){
    $block=str_replace("{{".$rk."}}",$rv,$block);
}
echo $block;