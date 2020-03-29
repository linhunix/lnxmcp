<?php
global $app_path;
$app_path=realpath(__DIR__);
//REMOVE THE HEADER IF NEED TO WORK ONLI AS A WEB PAGE 
//$lnxmcp_phar is an array can be used here to set the init config 
require __DIR__ . "/../../mcp/Head.php";
#require __DIR__ . "/../../dist/lnxmcp.phar";
var_dump(lnxmcpSetup(
    'add',#
    array(
        'name'=>'testctl',
        'require_add'=>array(),
        'require_del'=>array(),
        'remove'=>array(
            'type'=>'controller',
            'name'=>'test',
            "module"=>"Test",
            "vendor"=>"lnxtest"
        ),
        'install'=>array(
            'type'=>'controller',
            'name'=>'test',
            "module"=>"Test",
            "vendor"=>"lnxtest"
        )
    )
));