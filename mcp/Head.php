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
global $mcp_path, $app_path, $app_cfg,$app_work,$lnxmcp_phar, $lnxmcp_purl, $scopeInit, $cfg, $mcp;
// define path config
if (!isset($mcp_path)) {
    $mcp_path = __DIR__.'/';
}
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
    'mcp.path.module' => $app_path.'/mcp_module/',
    'app.path.query' => $app_path.'/App/dbj/',
    'app.path.menus' => $app_path.'/App/mnu/',
    'app.path.tags' => $app_path.'/App/tag/',
    'app.path.module' => $app_path.'/App/mod/',
    'app.path.template' => $app_path.'/App/tpl/',
    'app.path.workjob' => $app_path.'/work/job/',
    'app.path.cache' => $app_path.'/work/cache/',
    'app.path.session' => $app_path.'/work/session/',
    'app.path.exchange' => $app_path.'/work/exchange/',
    'app.path.sqllite' => $app_path.'/work/sqlite/',
    'app.path.work' => $app_work,
    'app.path.core' => $app_path.'/App/',
    'app.path.config' => $app_cfg,
    'app.path.language' => $app_path.'/App/lng/',
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
// CLASS LOADER
////////////////////////////////////////////////////////////////////////////////
$alrf = true;
$funpath = $mcp_path.'/Tools/Func.index.php';
$aldpath = $mcp_path.'/Tools/Class.index.php';
$stppath = $mcp_path.'/Tools/Step.index.php';
if (isset($scopeInit['phar']) == false) {
    $scopeInit['phar'] = false;
}
if ($scopeInit['phar'] == true) {
    if (file_exists($scopeInit['purl'].'/vendor/autoload.php')) {
        require $scopeInit['purl'].'/vendor/autoload.php';
        $alrf = false;
    }
    $funpath = $scopeInit['purl'].'/mcp/Tools/Func.index.php';
    $aldpath = $scopeInit['purl'].'/mcp/Tools/Class.index.php';
    $stppath = $scopeInit['purl'].'/mcp/Tools/Step.index.php';
}
if ($alrf) {
    if (file_exists($app_path.'/vendor/autoload.php')) {
        require $app_path.'/vendor/autoload.php';
    }
}
include_once $funpath;
if (class_exists("\Composer\Autoload\ClassLoader")) {
    $classLoader = new \Composer\Autoload\ClassLoader();
    $psr = array();
    if ($scopeInit['phar'] == true) {
        $classLoader->addPsr4('LinHUniX\\Mcp\\', $scopeInit['purl'].'/mcp/Mcp');
        $classLoader->addPsr4('LinHUniX\\Gfx\\', $scopeInit['purl'].'/mcp/Gfx');
        $classLoader->addPsr4('LinHUniX\\Pdo\\', $scopeInit['purl'].'/mcp/Pdo');
        $classLoader->addPsr4('LinHUniX\\Mail\\', $scopeInit['purl'].'/mcp/Mail');
        $classLoader->addPsr4('LinHUniX\\Ln4\\', $scopeInit['purl'].'/mcp/Ln4');
        $classLoader->addPsr4('LinHUniX\\Nsql\\', $scopeInit['purl'].'/mcp/Nsql');
        $classLoader->addPsr4('LinHUniX\\Auth\\', $scopeInit['purl'].'/mcp/Auth');
        $scopeInit['mcp.loader'] = 'AutoLoadPhar';
    } else {
        $classLoader->addPsr4('LinHUniX\\Mcp\\', $app_path.'/mcp/Mcp');
        $classLoader->addPsr4('LinHUniX\\Gfx\\', $app_path.'/mcp/Gfx');
        $classLoader->addPsr4('LinHUniX\\Pdo\\', $app_path.'/mcp/Pdo');
        $classLoader->addPsr4('LinHUniX\\Mail\\', $app_path.'/mcp/Mail');
        $classLoader->addPsr4('LinHUniX\\Ln4\\', $app_path.'/mcp/Ln4');
        $classLoader->addPsr4('LinHUniX\\Nsql\\', $app_path.'/mcp/Nsql');
        $classLoader->addPsr4('LinHUniX\\Auth\\', $app_path.'/mcp/Auth');
        $scopeInit['mcp.loader'] = 'AutoLoadSrc';
    }
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
if (isset($scopeInit['app.debug'])) {
    if ($scopeInit['app.debug'] == true) {
        if (isset($scopeInit['purl'])) {
            error_log('Load Mcp by '.$scopeInit['mcp.loader'].' ['.$scopeInit['phar'].']');
            error_log('PURL:'.$scopeInit['purl']);
            error_log('FILE:'.$aldpath);
        } else {
            error_log('Load Mcp by '.$scopeInit['mcp.loader'].' ['.$scopeInit['mcp.path'].']');
        }
    }
}
if (class_exists("\LinHUniX\Mcp\masterControlProgram")) {
    $mcp = new \LinHUniX\Mcp\masterControlProgram($scopeInit);
} else {
    $mcp = new masterControlProgram($scopeInit);
}
mcpErrorHandlerInit();
mcpShutDownInit();
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
