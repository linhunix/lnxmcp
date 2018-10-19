<?php
/**
 * LinHUniX Web Application Framework
 *
 * @author    Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version   GIT:2018-v2
 *
 */
////////////////////////////////////////////////////////////////////////////////
// PAGE MESSAGE CONTROL 
////////////////////////////////////////////////////////////////////////////////
error_reporting(E_ERROR);
ini_set("display_error",0);
ob_start();
////////////////////////////////////////////////////////////////////////////////
// ENV/CONFIG PATH AND CLASS
////////////////////////////////////////////////////////////////////////////////
// define path config
if (!isset($mcp_path)) {
    $mcp_path = __DIR__ . "/";
}
////////////////////////////////////////////////////////////////////////////////
// APP PATH AND CLASS
////////////////////////////////////////////////////////////////////////////////
if (!isset($app_path)) {
    $app_path = "";
}
if (empty($app_path)) {
    $app_path = $_SERVER['DOCUMENT_ROOT'];
    if (empty($app_path)) {
        $i = 0;
        $app_path = realpath (__DIR__."/../");
        if (empty($app_path) || ($app_path == "/")) {
            $app_path = realpath (dirname (__FILE__."/../"));
        }
        while ((!is_dir ($app_path . "/cfg")) && ($i < 5)) {
            $app_path = realpath ($app_path . "/../");
            $i++;
        }
        $_SERVER['DOCUMENT_ROOT'] = $app_path;
    }
}
$app_path = realpath ($app_path) . "/";
////////////////////////////////////////////////////////////////////////////////
// ENV/CONFIG JSON CONFIG AND SETTINGS
////////////////////////////////////////////////////////////////////////////////
try{
    if (file_exists ($app_path . "/cfg/mcp.settings.php")) {
        include $app_path . "/cfg/mcp.settings.php";
    }
    if (file_exists ($app_path . "/cfg/mcp.settings.json")) {
        $scopeInit=json_decode (file_get_contents ( $app_path . "/cfg/mcp.settings.json"),true);
    }
}catch(Exception $e){
    error_log("LNXMCP HEAD CFG ERROR:".$e->get_message);
}
////////////////////////////////////////////////////////////////////////////////
// SCOPE - INIT
////////////////////////////////////////////////////////////////////////////////
if (!isset($scopePdo)) {
    $scopePdo = array (
        "ENV" => array (
            "lnx.dst" => array (
                "hostname" => "LNX_DB_HOST",
                "database" => "LNX_DB_2_NAME",
                "username" => "LNX_DB_UID",
                "password" => "LNX_DB_PWD",
                "driver" => "mysql"
            ),
            "lnx.src" => array (
                "hostname" => "LNX_DB_HOST",
                "database" => "LNX_DB_1_NAME",
                "username" => "LNX_DB_UID",
                "password" => "LNX_DB_PWD",
                "driver" => "mysql"
            ),
        )
    );
}
if (!isset($scopeInit)) {
    $scopeInit = array (
        "app.def" => "LinHUniX",
        "app.path" => $app_path,
        "app.level" => "0",
        "app.debug"=>"false",
        "app.env"=>"dev",
        "app.evnlst" => array ('db_uid', 'db_pwd', 'db_host', 'db_1_name', 'db_2_name'),
        "mcp.path.module"=>$app_path."/mcp_module/",
        "app.path.query"=>$app_path."/App/dbj/",
        "app.path.menus"=>$app_path."/App/mnu/",
        "app.path.tags"=>$app_path."/App/tag/",
        "app.path.module"=>$app_path."/App/mod",
        "app.path.template"=>$app_path."/App/tpl/",
        "app.path.config"=>$app_path."/cfg/",
        "app.menu.InitCommon" => array (
            "pdo" => array ("module" => "Pdo", "type" => "serviceCommon", "input" => $scopePdo),
            "mail" => array ("module" => "Mail", "type" => "serviceCommon")
        ),
        "app.menu.InitApp" => array (),
    );
}
if(isset($lnxmcp_phar)){
    foreach($lnxmcp_phar as $lmpk=>$lmpv){
        $scopeInit[$lmpk]=$lmpv;
    }
}
////////////////////////////////////////////////////////////////////////////////
// ENVIRONMENT
////////////////////////////////////////////////////////////////////////////////
if (in_array ("ENVIRONMENT", $_SERVER)) {
    $scopeInit["app.env"] = $_SERVER["ENVIRONMENT"];
}
if (!isset($scopeInit["app.timezone"])) {
    $scopeInit["app.timezone"] = "Europe/London";
}
$scopeInit["mcp.path"] = $mcp_path;
$scopeInit["mcp.ver"] = file_get_contents ($mcp_path . "/mcp_version");
////////////////////////////////////////////////////////////////////////////////
// DATABASE
////////////////////////////////////////////////////////////////////////////////
if (!isset($scopeInit["app.legacydb"])) {
    $scopeInit["app.legacydb"] = false;
}
if (!isset($scopeInit["app.mysqli"])) {
    $scopeInit["app.mysqli"] = false;
}
////////////////////////////////////////////////////////////////////////////////
// CLASS LOADER
////////////////////////////////////////////////////////////////////////////////
$alrf = true;
$funpath = $mcp_path . '/Func.php';
$shlpath = $mcp_path . '/Shell.php';
$httpath = $mcp_path . '/Http.php';
$aldpath = $mcp_path . '/Load.php';
if(isset($lnxmcp_phar)==false){
    $lnxmcp_phar=array(
        "phar"=>false
    );
}
if ($lnxmcp_phar["phar"] == true) {
    if (file_exists ($lnxmcp_phar["purl"] . '/vendor/autoload.php')) {
        require ($lnxmcp_phar["purl"] . '/vendor/autoload.php');
        $alrf = false;
    }
    $funpath = $lnxmcp_phar["purl"] . '/mcp/LinHUniX/Func.php';
    $aldpath = $lnxmcp_phar["purl"] . '/mcp/LinHUniX/Load.php';
    $shlpath = $lnxmcp_phar["purl"] . '/mcp/LinHUniX/Shell.php';
    $httpath = $lnxmcp_phar["purl"] . '/mcp/LinHUniX/Http.php';
}
if ($alrf) {
    if (file_exists ($app_path . '/vendor/autoload.php')) {
        require ($app_path . '/vendor/autoload.php');
    }
}
include_once $funpath;
if (class_exists ("\Composer\Autoload\ClassLoader")) {
    $classLoader = new \Composer\Autoload\ClassLoader();
    $psr = array ();
    if ($lnxmcp_phar["phar"] == true) {
        $classLoader->addPsr4 ("LinHUniX\\Mcp\\", $lnxmcp_phar["purl"] . "/mcp/Mcp");
        $classLoader->addPsr4 ("LinHUniX\\Gfx\\", $lnxmcp_phar["purl"] . "/mcp/Gfx");
        $classLoader->addPsr4 ("LinHUniX\\Pdo\\", $lnxmcp_phar["purl"] . "/mcp/Pdo");
        $classLoader->addPsr4 ("LinHUniX\\Mail\\", $lnxmcp_phar["purl"] . "/mcp/Mail");
        $scopeInit["mcp.loader"] = "AutoLoadPhar";
    } else {
        $classLoader->addPsr4 ("LinHUniX\\Mcp\\", $app_path . "/mcp/Mcp");
        $classLoader->addPsr4 ("LinHUniX\\Gfx\\", $app_path . "/mcp/Gfx");
        $classLoader->addPsr4 ("LinHUniX\\Pdo\\", $app_path . "/mcp/Pdo");
        $classLoader->addPsr4 ("LinHUniX\\Mail\\", $app_path . "/mcp/Mail");
        $scopeInit["mcp.loader"] = "AutoLoadSrc";
    }
    $classLoader->register ();
    $classLoader->setUseIncludePath (true);
} else {
    if (file_exists ($aldpath)) {
        $scopeInit["mcp.loader"] = "SrcLoad";
        include_once ($aldpath);
    } else {
        $scopeInit["mcp.loader"] = "selfAutoLoad";
        selfAutoLoad ($app_path . DIRECTORY_SEPARATOR . "mcp");
    }
}
if (class_exists ("\LinHUniX\Mcp\masterControlProgram")) {
    $mcp = new \LinHUniX\Mcp\masterControlProgram($scopeInit);
} else {
    $mcp = new masterControlProgram($scopeInit);
}
mcpErrorHandlerInit ();
global $cfg, $mcp;
////////////////////////////////////////////////////////////////////////////////
// Menu Calling
////////////////////////////////////////////////////////////////////////////////
lnxmcp()->runMenu("InitCommon");
lnxmcp()->runMenu("InitApp");
$GLOBALS["mcp_preload"]=ob_clean();
ob_start();
////////////////////////////////////////////////////////////////////////////////
// Application solution
////////////////////////////////////////////////////////////////////////////////
if( lnxmcp()->getCfg("PreloadOnly")!=true){
    if (file_exists ($app_path . DIRECTORY_SEPARATOR . "main.php")) {
        include $app_path . DIRECTORY_SEPARATOR . "main.php";
        DumpAndExit ("End Of App");
    }else if(isset ($_SERVER["REQUEST_URI"])){
        if (file_exists ($httpath)) {
            include_once $httpath;
            mcpRunHttp();
        }
    }else if (isset($_REQUEST["Menu"])) {
        lnxmcp ()->runMenu ($_REQUEST["Menu"]);
    } else {
        $GLOBALS["mcp_preload"].=ob_clean();
        echo $GLOBALS["mcp_preload"];
        if (file_exists ($shlpath)) {
            include_once $shlpath;
            mcpRunShell ();
        } else {
            DumpAndExit ("App Not Configured!!!");
        }
    }
}