<?php
//REMOVE THE HEADER IF NEED TO WORK ONLI AS A WEB PAGE 
//$lnxmcp_phar is an array can be used here to set the init config 
$_SERVER["DOCUMENT_ROOT"]=__DIR__;
$scopePdo=array(
    "ENV" => array(
        "lnx.lite" => array(
            "config"=>"SCOPE",
            "path" => $_SERVER["DOCUMENT_ROOT"]."/work/sqlite/",
            "database" => "lnxmcp.work.db",
            "driver" => "sqlite"
        )
    )
);
$lnxmcp_phar=array(
    "app.level"=>0,
    "app.debug"=>true,
    "app.def"=>"nsqltest",
    "mcp.env"=>"nsqltest",
    "app.menu.InitCommon" => array(
        "pdo" => array("module" => "Pdo", "type" => "serviceCommon", "input" => $scopePdo),
        "gfx" => array("module" => "Gfx", "type" => "serviceCommon"),
        "nsql" => array("module" => "Nsql", "type" => "serviceCommon"),
        "mail" => array("module" => "Mail", "type" => "serviceCommon")
    )
);
#require __DIR__ . "/../../mcp/Head.php";
require __DIR__ . "/../../dist/lnxmcp.phar";
