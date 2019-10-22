<?php
//REMOVE THE HEADER IF NEED TO WORK ONLI AS A WEB PAGE 
//$lnxmcp_phar is an array can be used here to set the init config 
$app_path=realpath('./');
$scopePdo = array(
    'ENV' => array(
        'lnx.lite' => array(
            'config' => 'SCOPE',
            'path' => $app_path.'/work/sqlite/',
            'database' => 'lnxmcp.work.db',
            'driver' => 'sqlite',
        ),
        'lnx.mydata'=>array(
            'config' => 'SCOPE',
            'hostname' => 'localhost',
            'database' => 'lnxmcp',
            'username' => 'lnxmcp',
            'password' => '1234',
            'driver' => 'mysql',
        )
    ),
);
$lnxmcp_phar=array(
    'app.menu.InitCommon' => array(
        'pdo' => array('module' => 'Pdo', 'type' => 'serviceCommon', 'input' => $scopePdo),
        'gfx' => array('module' => 'Gfx', 'type' => 'serviceCommon'),
        'auth' => array('module' => 'Auth', 'type' => 'serviceCommon'),
        'mail' => array('module' => 'Mail', 'type' => 'serviceCommon'),
    )
);
require __DIR__ . "/../../mcp/Head.php";
#require __DIR__ . "/../../dist/lnxmcp.phar";
