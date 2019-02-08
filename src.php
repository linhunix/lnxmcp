<?php
//REMOVE THE HEADER IF NEED TO WORK ONLI AS A WEB PAGE 
//$lnxmcp_phar is an array can be used here to set the init config 
$lnxmcp_phar = array(
    "app.support.mail"=>"andrea.morello@linhunix.com",
    "app.level" => "0",
    "app.debug" => true
);
require __DIR__ . "/mcp/Head.php";
#require __DIR__ . "/dist/lnxmcp.phar";
