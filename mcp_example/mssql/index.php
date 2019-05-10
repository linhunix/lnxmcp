<?php
//REMOVE THE HEADER IF NEED TO WORK ONLI AS A WEB PAGE 
//$lnxmcp_phar is an array can be used here to set the init config 
/*

*/

$app_path=__DIR__;
include __DIR__."/../config.php";
$scopePdo = array(
	"ENV" => array(
		"lnx.lite" => array(
			"config"=>"SCOPE",
			"path" => $app_path."/work/sqlite/",
			"database" => "lnxmcp.work.db",
			"driver" => "sqlite"
		),
		"mssql" => array(
			"config"=>"SCOPE",
			"hostname" => "SERVER\sqlserver",
			"database" => $db.";ConnectionPooling=0",
			"username" => $un,
			"password" => $pw,
			"driver" => "mssql"
		),
	)
);
$lnxmcp_phar=array(
	"app.def"=>"digitalight",
	"app.level"=>0,
	"app.debug"=>true,
    "app.menu.InitCommon" => array(
        "pdo" => array("module" => "Pdo", "type" => "serviceCommon", "input" => $scopePdo),
        "gfx" => array("module" => "Gfx", "type" => "serviceCommon"),
        //"auth" => array("module" => "Auth", "type" => "serviceCommon"),
        "mail" => array("module" => "Mail", "type" => "serviceCommon")
    )
);
require __DIR__ . "/lnxmcp.phar";
lnxmcp()->RemCommon();
$mssql=lnxmcp()->getResource("Driver.mssql");
LnxMcpFullDebugOn();
lnxmcp()->Rem($mssql->simpleQuery("SELECT * FROM sys.databases;"));


