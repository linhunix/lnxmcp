#!/usr/bin/env php
<?php
    putenv("MCP_MODE=TEST");
    $_SERVER["DOCUMENT_ROOT"]=__DIR__;
    $lnxmcp_phar=array(
        "app.level"=>0,
        "app.debug"=>true,
        "mcp.env"=>"TEST"
    );
    include __DIR__."/src.php";
    if (isset($argv[1])){
        lnxmcpChk($argv[1]);
    }else{
        lnxmcpChk("mcp/test");
    }
 ?>
