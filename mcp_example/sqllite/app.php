<?php
//REMOVE THE HEADER IF NEED TO WORK ONLI AS A WEB PAGE 
//$lnxmcp_phar is an array can be used here to set the init config 
$_SERVER["DOCUMENT_ROOT"]=__DIR__;
    $lnxmcp_phar=array(
        "app.level"=>0,
        "app.debug"=>true,
        "mcp.env"=>"TEST"
    );
#require __DIR__ . "/../../mcp/Head.php";
require __DIR__ . "/../../dist/lnxmcp.phar";
