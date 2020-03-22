<?php

/**
 * LinHUniX Web Application Framework.
 *
 * @author    Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 *
 * @version   GIT:2018-v2
 */
////////////////////////////////////////////////////////////////////////////////
// PAGE MESSAGE CONTROL
////////////////////////////////////////////////////////////////////////////////
error_reporting(E_ERROR);
ini_set('display_error', 0);
ob_start();
////////////////////////////////////////////////////////////////////////////////
// ENV/CONFIG PATH AND CLASS
////////////////////////////////////////////////////////////////////////////////
global $mcp_path, $app_path, $app_cfg, $app_work, $app_core,$app_user ,$lnxmcp_phar, $lnxmcp_purl, $scopeInit, $cfg, $mcp;
// define path config
if (!isset($mcp_path)) {
    $mcp_path = __DIR__.'/';
}
$mcp_mpath=realpath($mcp_path.'/../');
////////////////////////////////////////////////////////////////////////////////
// APP PATH AND CLASS
////////////////////////////////////////////////////////////////////////////////
if (!isset($app_path)) {
    $app_path = '';
}
if (empty($app_path)) {
    $app_path = $_SERVER['DOCUMENT_ROOT'];
    if (empty($app_path)) {
        $i = 0;
        $app_path = realpath(__DIR__.'/../');
        if (empty($app_path) || ($app_path == '/')) {
            $app_path = realpath(dirname(__FILE__.'/../'));
        }
        while ((!is_dir($app_path.'/cfg')) && ($i < 5)) {
            $app_path = realpath($app_path.'/../');
            ++$i;
        }
        $_SERVER['DOCUMENT_ROOT'] = $app_path;
    }
}
$app_path = realpath($app_path).'/';
if (!isset($app_cfg)){
    $app_cfg=$app_path.'/cfg/';
}
if (!isset($app_work)){
    $app_work=$app_path.'/work/';
}
if (!isset($app_core)){
    $app_core=$app_path.'/App/';
}
if (!isset($app_user)){
    $app_user=$app_path.'/usr/';
}
////////////////////////////////////////////////////////////////////////////////
// SCOPE - INIT
////////////////////////////////////////////////////////////////////////////////
if (!isset($scopePdo)) {
    $scopePdo = array(
        'ENV' => array(
            'lnx.lite' => array(
                'config' => 'SCOPE',
                'path' => $app_work.'/sqlite/',
                'database' => 'lnxmcp.work.db',
                'driver' => 'sqlite',
            ),
        ),
    );
}
/*
    'lnx.myctl' => array(
        'config' => 'ENV',
        'hostname' => 'LNX_MYCTL_DB_HOST',
        'database' => 'LNX_MYCTL_DB_NAME',
        'username' => 'LNX_MYCTL_DB_UID',
        'password' => 'LNX_MYCTL_DB_PWD',
        'driver' => 'mysql',
    ),
    'lnx.mydata' => array(
        'config' => 'ENV',
        'hostname' => 'LNX_MYDT_DB_HOST',
        'database' => 'LNX_MYDT_DB_NAME',
        'username' => 'LNX_MYDT_DB_UID',
        'password' => 'LNX_MYDT_DB_PWD',
        'driver' => 'mysql',
    ),
 */
if (!isset($scopeInit)) {
    $scopeInit = array();
}
foreach (array(
    'app.def' => 'LinHUniX',
    'app.lang' => 'en',
    'app.type' => 'lib',
    'app.path' => $app_path,
    'app.level' => '70',
    'app.debug' => 'false',
    'app.env' => 'dev',
    'app.support.name' => 'LinHuniX Support Team',
    'app.support.mail' => 'support@linhunix.com',
    'app.support.onerrorsend' => false,
    'app.evnlst' => array('db_uid', 'db_pwd', 'db_host', 'db_1_name', 'db_2_name'),
    'mcp.path.module' => $mcp_mpath.'/mcp_modules/',
    'app.path.core' => $app_core,
    'app.path.query' => $app_core.'/dbj/',
    'app.path.menus' => $app_core.'/mnu/',
    'app.path.tags' => $app_core.'/tag/',
    'app.path.module' => $app_core.'/mod/',
    'app.path.template' => $app_core.'/tpl/',
    'app.path.language' => $app_core.'/lng/',
    'app.path.userfiles' => $app_user,
    'app.path.work' => $app_work,
    'app.path.workjob' => $app_work.'/job/',
    'app.path.cache' => $app_work.'/cache/',
    'app.path.session' => $app_work.'/session/',
    'app.path.exchange' => $app_work.'/exchange/',
    'app.path.sqllite' => $app_work.'/sqlite/',
    'app.path.config' => $app_cfg,
    'app.path.pbkac' => '/tmp/',
    'app.menu.InitCommon' => array(
        'pdo' => array('module' => 'Pdo', 'type' => 'serviceCommon', 'input' => $scopePdo),
        'gfx' => array('module' => 'Gfx', 'type' => 'serviceCommon'),
        'auth' => array('module' => 'Auth', 'type' => 'serviceCommon'),
        'mail' => array('module' => 'Mail', 'type' => 'serviceCommon'),
    ),
    'app.menu.InitApp' => array(),
) as $sik => $siv) {
    if (!isset($scopeInit[$sik])) {
        $scopeInit[$sik] = $siv;
    }
}

if (isset($lnxmcp_phar)) {
    if (isset($lnxmcp_phar['app.debug'])) {
        if ($lnxmcp_phar['app.debug'] == true) {
            error_log(var_export($lnxmcp_phar, 1));
        }
    }
    foreach ($lnxmcp_phar as $lmpk => $lmpv) {
        $scopeInit[$lmpk] = $lmpv;
    }
}
/// reload primary vars
$app_path=$scopeInit["app.path"];
$app_work=$scopeInit["app.path.work"];
$app_cfg=$scopeInit["app.path.config"];
$app_core=$scopeInit["app.path.core"];
$app_user=$scopeInit["app.path.userfiles"];

////////////////////////////////////////////////////////////////////////////////
// ENV/CONFIG JSON CONFIG AND SETTINGS
////////////////////////////////////////////////////////////////////////////////
try {
    if (file_exists($app_cfg.'/mcp.settings.json')) {
        $loadscope = json_decode(file_get_contents($app_cfg.'/mcp.settings.json'), true);
        if (is_array($loadscope)) {
            foreach ($loadscope as $lsk => $lsv) {
                $scopeInit[$lsk] = $lsv;
            }
        }
    }
    if (file_exists($app_cfg.'/mcp.default.json')) {
        $loadscope = json_decode(file_get_contents($app_cfg.'/mcp.default.json'), true);
        if (is_array($loadscope)) {
            foreach ($loadscope as $lsk => $lsv) {
                $scopeInit[$lsk] = $lsv;
            }
        }
    }
    if (isset($_ENV['MCP_MODE'])) {
        $hostcfg = $app_cfg.'/mcp.mode.'.$_ENV['MCP_MODE'].'.json';
        if (file_exists($hostcfg)) {
            $loadscope = json_decode(file_get_contents($hostcfg), true);
            if (is_array($loadscope)) {
                foreach ($loadscope as $lsk => $lsv) {
                    $scopeInit[$lsk] = $lsv;
                }
            }
        }
    }
    if (isset($_SERVER['HTTP_HOST'])) {
        $hostcfg = $app_cfg.'/mcp.site.'.$_SERVER['HTTP_HOST'].'.json';
        if (file_exists($hostcfg)) {
            $loadscope = json_decode(file_get_contents($hostcfg), true);
            if (is_array($loadscope)) {
                foreach ($loadscope as $lsk => $lsv) {
                    $scopeInit[$lsk] = $lsv;
                }
            }
        }
    }
    if (isset($_SERVER['SERVER_NAME'])) {
        $hostcfg = $app_cfg.'/mcp.server.'.$_SERVER['SERVER_NAME'].'.json';
        if (file_exists($hostcfg)) {
            $loadscope = json_decode(file_get_contents($hostcfg), true);
            if (is_array($loadscope)) {
                foreach ($loadscope as $lsk => $lsv) {
                    $scopeInit[$lsk] = $lsv;
                }
            }
        }
    }
    if (isset($scopeInit['preload'])) {
        if (file_exists($scopeInit['preload'])) {
            include $scopeInit['preload'];
        }
    } else {
        if (file_exists($app_cfg.'/mcp.preload.php')) {
            include $app_cfg.'/mcp.preload.php';
        }
    }
    if (isset($scopeInit['loadenv'])) {
        if (is_array($scopeInit['loadenv'])) {
            foreach ($scopeInit['loadenv'] as $k => $v) {
                putenv($k.'='.$v);
                $_ENV[$k] = $v;
                $_SERVER[$k] = $v;
            }
        } else {
            putenv($scopeInit['loadenv']);
        }
    }
} catch (Exception $e) {
    error_log('LNXMCP HEAD CFG ERROR:'.$e->get_message);
}
$app_path=$scopeInit["app.path"];
$app_work=$scopeInit["app.path.work"];
$app_cfg=$scopeInit["app.path.config"];
$app_core=$scopeInit["app.path.core"];

$app_user=$scopeInit["app.path.userfiles"];
if (!is_dir($app_user)){
    if (is_dir($app_path.DIRECTORY_SEPARATOR.$app_user)){
	$app_user=$app_path.DIRECTORY_SEPARATOR.$app_user;
	$scopeInit["app.path.userfiles"]=$app_user;
    }
}
////////////////////////////////////////////////////////////////////////////////
// ENVIRONMENT
////////////////////////////////////////////////////////////////////////////////
if (isset($_SERVER['ENVIRONMENT'])) {
    $scopeInit['app.env'] = $_SERVER['ENVIRONMENT'];
}
if (!isset($scopeInit['mcp.env'])) {
    $scopeInit['mcp.env'] = 'PROD';
}
if (!isset($scopeInit['app.env'])) {
    $scopeInit['app.env'] = 'PROD';
}
if (getenv('MCP_MODE') != '') {
    $scopeInit['mcp.env'] = getenv('MCP_MODE');
}
if ($scopeInit['mcp.env'] == 'TEST') {
    $scopeInit['app.level'] = '0';
    $scopeInit['app.debug'] = true;
}
if (!isset($scopeInit['app.timezone'])) {
    $scopeInit['app.timezone'] = 'Europe/London';
}
$scopeInit['mcp.path'] = $mcp_path;
$scopeInit['mcp.path.root'] = $mcp_mpath;
$scopeInit['mcp.ver'] = file_get_contents($mcp_path.'/mcp_version');
////////////////////////////////////////////////////////////////////////////////
// DATABASE
////////////////////////////////////////////////////////////////////////////////
if (!isset($scopeInit['app.legacydb'])) {
    $scopeInit['app.legacydb'] = false;
}
if (!isset($scopeInit['app.mysqli'])) {
    $scopeInit['app.mysqli'] = false;
}
////////////////////////////////////////////////////////////////////////////////
// CLASS LOADER - FUNCTION 
////////////////////////////////////////////////////////////////////////////////
$common_path=$mcp_path;
$common_xpath=$mcp_mpath.'/mcp_modules/';
$common_apath=$mcp_mpath;

if (isset($scopeInit['phar']) == false) {
    $scopeInit['mcp.loader'] = 'AutoLoadSrc';
    $scopeInit['phar'] = false;
}
if ($scopeInit['phar'] == true) {
    $scopeInit['mcp.loader'] = 'AutoLoadPhar';
    $common_path=$scopeInit['purl'].'/mcp/';
    $common_xpath=$scopeInit['purl'].'/mcp_modules/';
    $common_apath=$scopeInit['purl'];

}
$funpath = $common_path.'/Tools/Func.index.php';
$clspath = $common_path.'/Tools/Class.index.php';
$aldpath = $common_path.'/Tools/Alt.index.php';
$stppath = $common_path.'/Tools/Step.index.php';
////////////////////////////////////////////////////////////////////////////////
// CLASS LOADER - AUTOLOAD 
////////////////////////////////////////////////////////////////////////////////
if (file_exists($common_apath.'/vendor/autoload.php')) {
    require $common_apath.'/vendor/autoload.php';
}
else
{
    if (file_exists($app_path.'/vendor/autoload.php')) {
        require $app_path.'/vendor/autoload.php';
    }
}
////////////////////////////////////////////////////////////////////////////////
// CLASS LOADER - AUTOLOAD 
////////////////////////////////////////////////////////////////////////////////
include_once $funpath;
include_once $clspath;
if (class_exists("\Composer\Autoload\ClassLoader")) {
    $classLoader = new \Composer\Autoload\ClassLoader();
    $psr = array();
    $classLoader->addPsr4('LinHUniX\\Mcp\\', $common_path.'/Mcp');
    $classLoader->addPsr4('LinHUniX\\Gfx\\', $common_path.'/Gfx');
    $classLoader->addPsr4('LinHUniX\\Pdo\\', $common_path.'/Pdo');
    $classLoader->addPsr4('LinHUniX\\Mail\\', $common_path.'/Mail');
    $classLoader->addPsr4('LinHUniX\\Auth\\', $common_path.'/Auth');
    $classLoader->addPsr4('LinHUniX\\Cron\\', $common_path.'/Cron');
    $classLoader->register();
    $classLoader->setUseIncludePath(true);
} else {
    if (file_exists($aldpath)) {
        $scopeInit['mcp.loader'] = 'SrcLoad';
        include_once $aldpath;
    } else {
        $scopeInit['mcp.loader'] = 'selfAutoLoad';
        selfAutoLoad($app_path.DIRECTORY_SEPARATOR.'mcp');
    }
}
////////////////////////////////////////////////////////////////////////////////
// CLASS LOADER - MCP CORE 
////////////////////////////////////////////////////////////////////////////////
if (class_exists("\LinHUniX\Mcp\masterControlProgram")) {
    $mcp = new \LinHUniX\Mcp\masterControlProgram($scopeInit);
} else {
    $mcp = new masterControlProgram($scopeInit);
}
define('LNXMCP_APP_DEF',lnxmcp()->getCfg('app.def'));
define('LNXMCP_APP_PATH',$app_path);
define('LNXMCP_CFG_PATH',$app_cfg);
define('LNXMCP_USR_PATH',$app_user);
define('LNXMCP_WRK_PATH',$app_work);
define('LNXMCP_MCP_PATH',$mcp_path);
define('LNXMCP_MCP_VER',lnxmcp()->getCfg('mcp.ver'));
mcpErrorHandlerInit();
mcpShutDownInit();
////////////////////////////////////////////////////////////////////////////////
// CLASS LOADER - MCP_MODULED 
////////////////////////////////////////////////////////////////////////////////
lnxmcp()->addModule($common_xpath.'/Adm/',array('vendor'=>'LinHUniX','module'=>'LnxMcpAdm','version'=>$scopeInit['mcp.ver']));
lnxmcp()->addModule($common_xpath.'/Adm/Shell/',array('vendor'=>'LinHUniX','module'=>'LnxMcpAdmShell','version'=>$scopeInit['mcp.ver']));
lnxmcp()->addModule($common_xpath.'/Adm/Httpd/',array('vendor'=>'LinHUniX','module'=>'LnxMcpAdmHttpd','version'=>$scopeInit['mcp.ver']));
lnxmcp()->addModule($common_xpath.'/Ln4/',array('vendor'=>'LinHUniX','module'=>'Ln4','version'=>$scopeInit['mcp.ver']));
lnxmcp()->addModule($common_xpath.'/Nsql/',array('vendor'=>'LinHUniX','module'=>'Nsql','version'=>$scopeInit['mcp.ver']));
lnxmcp()->addModule($common_xpath.'/Lsql/',array('vendor'=>'LinHUniX','module'=>'Lsql','version'=>$scopeInit['mcp.ver']));
lnxmcp()->addModule($common_xpath.'/Upload/',array('vendor'=>'LinHUniX','module'=>'Upload','version'=>$scopeInit['mcp.ver']));
////////////////////////////////////////////////////////////////////////////////
// CLASS LOADER - SUMMARY 
////////////////////////////////////////////////////////////////////////////////
if (isset($scopeInit['app.debug'])) {
    if ($scopeInit['app.debug'] == true) {
        error_log('Load Mcp by '.$scopeInit['mcp.loader'].' ['.$common_path.']');
    }
}
////////////////////////////////////////////////////////////////////////////////
// Menu Calling
////////////////////////////////////////////////////////////////////////////////
lnxmcp()->runMenu('InitCommon');
lnxmcp()->runMenu('InitApp');
$GLOBALS['mcp_preload'] = ob_get_clean();
ob_start();
////////////////////////////////////////////////////////////////////////////////
// CHECK STANDARD STEP
////////////////////////////////////////////////////////////////////////////////
if (lnxmcp()->getCfg('PreloadOnly') != true) {
    include_once $stppath;
}
////////////////////////////////////////////////////////////////////////////////
// CHECK POSTLOAD STEP
////////////////////////////////////////////////////////////////////////////////
$postload=lnxmcp()->getCfg("app.postload.cmd");
if (is_array($postload)){
    lnxmcp()->runCommand($postload,array());
}
