#!/usr/bin/env php
<?php 
    putenv("MCP_MODE=TEST");
    $_SERVER["DOCUMENT_ROOT"]=__DIR__; include __DIR__."/app.php";
    if (isset($argv[1])){
        lnxmcpChk($argv[1]);
    }else{
        lnxmcpChk("mcp/test");
    }
 ?>