<?php
/**
 * LinHUniX Web Application Framework
 *
 * @author Andrea Morello <andrea.morello@freetimers.com>
 * @copyright LinHUniX Communications Ltd, 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 *
 */
////////////////////////////////////////////////////////////////////////////////
// ENV/CONFIG PATH AND CLASS
////////////////////////////////////////////////////////////////////////////////
// define path config
if (!isset($app_path))
{
    $app_path = "";
}
if (!isset($mcp_path))
{
    $mcp_path = __DIR__."/";
}
if (empty($app_path))
{
    $app_path = $_SERVER['DOCUMENT_ROOT'];
    if (empty($app_path))
    {
        $i = 0;
        $app_path = realpath(__DIR__);
        if (empty($app_path) || ($app_path == "/"))
        {
            if (isset($lnxmcp_vers["path"]))
            {
                $app_path = realpath($lnxmcp_vers["path"]);
            }
        }
        if (empty($app_path) || ($app_path == "/"))
        {
            $app_path = realpath(dirname(__FILE__));
        }
        while ((!is_dir($app_path . "/cfg")) && ($i < 5))
        {
            $app_path = realpath($app_path . "/../");
            $i++;
        }
        $_SERVER['DOCUMENT_ROOT'] = $app_path;
    }
}
$app_path = realpath($app_path) . "/";
////////////////////////////////////////////////////////////////////////////////
// ENV/CONFIG JSON CONFIG AND SETTINGS
////////////////////////////////////////////////////////////////////////////////
if (file_exists($app_path . "/cfg/.settings.php"))
{
    include $app_path . "/cfg/.settings.php";
}
if (!isset($lnxmcp_vers))
{
    $lnxmcp_vers = array(
        "ver" => "1.0.1",
        "phar" => false,
    );
    if (file_exists($app_path . "/VERSION"))
    {
        $lnxmcp_vers["ver"] = file_get_contents($app_path . "/VERSION");
    }
}
////////////////////////////////////////////////////////////////////////////////
// ENVIRONMENT
////////////////////////////////////////////////////////////////////////////////
$lnxmcp_vers["env"]="";
if (in_array ("ENVIRONMENT",$_SERVER)){
    $lnxmcp_vers["env"]=$_SERVER["ENVIRONMENT"];
}
if ($lnxmcp_vers["env"]==""){
    $lnxmcp_vers["env"]="debug";
}
if (!isset($scopeInit))
{
    $scopeInit = array(
        "app.def" => "LinHUniX",
        "app.path" => $app_path,
        "app.level" => "DEBUG",
        "app.evnlst" => ['db_uid', 'db_pwd', 'db_host', 'db_1_name', 'db_2_name']
    );
}
if (!isset($scopePdo))
{
    $scopePdo = array(
        "ENV" => [
            "ft.dst" => array(
                "hostname" => "FT_DB_HOST",
                "database" => "FT_DB_2_NAME",
                "username" => "FT_DB_UID",
                "password" => "FT_DB_PWD",
                "driver" => "mysql"
            ),
            "ft.src" => array(
                "hostname" => "FT_DB_HOST",
                "database" => "FT_DB_1_NAME",
                "username" => "FT_DB_UID",
                "password" => "FT_DB_PWD",
                "driver" => "mysql"
            ),
        ]
    );
}
foreach ($lnxmcp_vers as $ftk => $ftv)
{
    $scopeInit["app.".$ftk] = $ftv;
}
$scopeInit["mcp.path"] = $mcp_path;
$alrf = true;
$funpath = $mcp_path. '/functions.php';
if ($lnxmcp_vers["phar"] == true)
{
    if (file_exists($lnxmcp_vers["purl"] . '/vendor/autoload.php'))
    {
        require($lnxmcp_vers["purl"] . '/vendor/autoload.php');
        $alrf = false;
    }
    $funpath = $lnxmcp_vers["purl"] . '/src/LinHUniX/functions.php';
}
if ($alrf) {
    if (file_exists ($app_path . '/vendor/autoload.php'))
    {
        require ($app_path . '/vendor/autoload.php');
    }
}
if (!isset($scopeInit["app.timezone"]))
{
    $scopeInit["app.timezone"] = "Europe/London";
}
if (!isset($scopeInit["app.legacydb"]))
{
    $scopeInit["app.legacydb"] = false;
}
if (!isset($scopeInit["app.mysqli"]))
{
    $scopeInit["app.mysqli"] = false;
}
if (class_exists ("\Composer\Autoload\ClassLoader")){
    $classLoader = new \Composer\Autoload\ClassLoader();
    $psr = array();
    if ($lnxmcp_vers["phar"] == true)
    {
        $classLoader->addPsr4("LinHUniX\\Mcp\\", $lnxmcp_vers["purl"] . "/src/Mcp");
        $classLoader->addPsr4("LinHUniX\\Pdo\\", $lnxmcp_vers["purl"] . "/src/Pdo");
        $classLoader->addPsr4("LinHUniX\\Html\\", $lnxmcp_vers["purl"] . "/src/Html");
    } else
    {
        $classLoader->addPsr4("LinHUniX\\Mcp\\", $app_path . "/src/Mcp");
        $classLoader->addPsr4("LinHUniX\\Pdo\\", $app_path . "/src/Pdo");
        $classLoader->addPsr4("LinHUniX\\Html\\", $app_path . "/src/Html");
    }
    $classLoader->register();
    $classLoader->setUseIncludePath(true);
}else{
    include_once ($mcp_path."/autoload.php");
}
$mcp = new \LinHUniX\Mcp\masterControlProgram($scopeInit);
global $cfg;
include_once $funpath;
////////////////////////////////////////////////////////////////////////////////
// DB/CONFIG
////////////////////////////////////////////////////////////////////////////////
lnxmcp()->serviceCommon("pdo", true, $scopePdo, "Pdo");
if ($scopeInit["app.legacydb"])
{
    lnxmcp()->loaderCommon("mysqlLegacy", "Pdo", $scopeInit);
}
lnxmcp()->api("database", true, array(), "Legacy");
lnxmcp()->loaderApp("config", "Legacy");
global $olddatabase_name;
global $newdatabase_name;
global $db;
global $cn;
////////////////////////////////////////////////////////////////////////////////
// shell soluction 
////////////////////////////////////////////////////////////////////////////////
if (isset($argv[2]))
{
    lnxmcp()->debugVar("head-shell", "argv", $argv);
    switch ($argv[1])
    {
        case "help":
            echo "lnxmcp <action> <args.....>\n";
            break;
        case "lnxmcp":
            lnxmcp()->$argv[2]();
            break;
        case "lnxmcp-ctl":
            lnxmcp()->controllerCommon($argv[2], false, $argv, $arg[3]);
            break;
        case "lnxmcp-bcl":
            lnxmcp()->showFullCommonBlock($argv[2], $argv, $arg[3]);
            break;
        case "lnxmcp-dmp":
            var_dump($cfg);
            break;
        case "lnxmcp-pharize":
            include_once $mcp_path."pharize.php";
            if ($argv[2] == "shell")
            {
                pharizeShell::run();
            } else
            {
                pharizeBase::run();
            }
            break;
        default:
            echo "not valid argv";
            var_dump($argv);
    }
}