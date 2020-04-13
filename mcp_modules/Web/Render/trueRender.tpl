<?php
/*
this render can be use with the vars 
$scopeIn['truevar'] string
 and 
$scopeIn[$scopeIn['truevar']] array 
ex : <tagmcp name="true" type="render" module="web" vendor="LinHUniX" scope-json-in='{"truevar":"myvar","myvar":true }' >

*/
if (isset($scopeIn['truevar'])){
    $bovar=$scopeIn['truevar'];
    if (isset($scopeIn[$bovar])){
        if ($scopeIn[$bovar]==true){
            echo $scopeIn['blockIn'];
        }
    }
}
