<?php
/*
this render can be use with the vars 
$scopeIn['falsevar'] string
 and 
$scopeIn[$scopeIn['falsevar']] array 
ex : <tagmcp name="false" type="render" module="web" vendor="LinHUniX" scope-json-in='{"falsevar":"myvar","myvar":false }' >
*/
if (isset($scopeIn['falsevar'])){
    $isfalse=true;
    $bovar=$scopeIn['falsevar'];
    if (isset($scopeIn[$bovar])){
        if ($scopeIn[$bovar]==true){
            $isfalse=false;
        }
    }
    if ($isfalse==true){
        echo $scopeIn['blockIn'];
    }
}