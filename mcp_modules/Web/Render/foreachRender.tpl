<?php
/**
this render can be use with the vars 
$scopeIn['foreachvar'] string
 and 
$scopeIn[$scopeIn['foreachvar']] array 
ex : <tagmcp name="foreach" type="render" module="web" vendor="LinHUniX" scope-json-in='{"foreachvar":"myvar","myvar":{"ka":"va","kb":"vb"}}' >
tag :
{{fe_key}} {{fe_value}}
if value is array {{fev_[key]}}
**/
if (isset($scopeIn['foreachvar'])){
    $fevar=$scopeIn['foreachvar'];
    if (isset($scopeIn[$fevar])){
        if (is_array($scopeIn[$fevar])) {
            foreach($scopeIn[$fevar] as $fek =>$fev ){
                $myblock=$scopeIn['blockIn'];
                $myblock=str_ireplace('{{fe_key}}',$fek,$myblock);
                $myblock=str_ireplace('{{fe_value}}',print_r($fev,1),$myblock);
                if (is_array($fev)){
                    foreach($fev as $fevk=>$fevd) {
                        $myblock=str_ireplace('{{fe_'.$fevk.'}}',$fevd,$myblock);
                    }
                }
                echo $myblock;
            }
        }
    }
}
