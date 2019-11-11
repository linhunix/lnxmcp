<?php
/**
 * LinHUniX Web Application Framework.
 *
 * @author    Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 *
 * @version   GIT:2018-v3
 */

namespace LinHUniX\Mcp;

use LinHUniX\Mcp\Provider\settingsProviderModel;
use LinHUniX\Mcp\Model\mcpServiceProviderModelClass;
use LinHUniX\Mcp\Model\mcpConfigArrayModelClass;
use LinHUniX\Mcp\Component\mcpMenuClass;
use LinHUniX\Mcp\Component\mcpProxyClass;
use LinHUniX\Mcp\Component\mcpTemplateClass;
use LinHUniX\Mcp\Component\mcpLanguageClass;
use LinHUniX\Mcp\Component\mcpMailClass;
use LinHUniX\Mcp\Component\mcpApiClass;

/*
 * this Master Control Programs Class is to prepare
 * to use a slim or simphony controller
 * as a difference that the Ashley LinHUniX MVC class is to easy implemts
 * because use only a easy class mcpBaseModelClass and two methods run and check
 * and support the $GLOBAL vars like "cfg"
 * this class are exportable on the future as abstact controller
 *
 * @see [vendor]/mcp/Head.php
 */

final class masterControlProgram
{
    const CLASS_LOGGER = 'logClass';

    /**
     * @var string path of the Applications locations
     */
    private $pathapp;

    /**
     * getPathApp Path Application Folder.
     *
     * @return string
     */
    public function getPathApp()
    {
        return $this->pathapp;
    }

    /**
     * @var string path of the template positions on the code
     */
    private $pathtpl;

    /**
     * getPathTpl : Path Template Folder.
     *
     * @return string
     */
    public function getPathTpl()
    {
        return $this->pathtpl;
    }

    /**
     * @var string path of the module positions on the code
     */
    private $pathsrc;

    /**
     * getPathTpl : Path Application Module Source  Folder.
     *
     * @return string
     */
    public function getPathSrc()
    {
        return $this->pathsrc;
    }

    /**
     * @var string path of the template positions on the code
     */
    private $pathmcp;

    /**
     * getPathMcp : Path Mxp Folder.
     *
     * @return string
     */
    public function getPathMcp()
    {
        return $this->pathmcp;
    }

    /**
     * @var Slim Content as a test of integrations
     */
    private $cfg; // is a test class to integrate slim on the code
    /**
     * Short Name of this applicationmcpLanguageClass.
     *
     * @var string
     */
    private $common; // is a test class to integrate slim on the code
    /**
     * Short Name of this application.
     *
     * @var string
     */
    private $buffer; // is a test class to integrate slim on the code
    /**
     * Short Name of this application.
     *
     * @var string
     */
    private $event; // is a test class to integrate slim on the code
    /**
     * Short Name of this application.
     *
     * @var string
     */
    private $defapp;

    /**
     * getDefApp : Path Mxp Folder.
     *
     * @return string
     */
    public function getDefApp()
    {
        return $this->defapp;
    }

    /**
     * Short Name of the vendors.
     *
     * @var string
     */
    private $defvnd = 'LinHUniX';

    /**
     * getDefApp : Path Mxp Folder.
     *
     * @return string
     */
    public function getDefVnd()
    {
        return $this->defvnd;
    }

    /**
     * @var class
     */
    private $mcpCore;
    /**
     * @var class
     */
    private $mcpLogging;
    /**
     * @var class
     */
    private $mcpTools;
    /**
     * start  variable.
     *
     * @var float
     */
    private $startTime;
    /////////////////////////////////////////////////////////////////////////////
    // CONSTRUCTOR AND INIT  - LEGACY SETTING
    /////////////////////////////////////////////////////////////////////////////

    /**
     * Create a slim app integration, add container and set the log as.
     *
     * @param Container $cfg was load as master controller (lnxmcp)
     */
    public function __construct(array $scopeIn)
    {
        $this->startTime = $this->getFloatTime();
        $this->pathapp = $scopeIn['app.path.core'];
        $this->common = array();

        $this->event = array();
        $this->defapp = ucwords($scopeIn['app.def']);
        if (isset($scopeIn['app.path.module'])) {
            $this->pathsrc = $scopeIn['app.path.module'];
        } else {
            $this->pathsrc = $this->pathapp.'mod/';
        }
        if (isset($scopeIn['app.path.template'])) {
            $this->pathtpl = $scopeIn['app.path.template'];
        } else {
            $this->pathtpl = $this->pathapp.'tpl/';
        }
        if (isset($scopeIn['mcp.path.module'])) {
            $this->pathmcp = $scopeIn['mcp.path.module'];
        } else {
            $this->pathmcp = $scopeIn['app.path'].'mcp_module/';
        }
        $this->cfg = new mcpConfigArrayModelClass();
        $this->cfg['php'] = PHP_VERSION;
        $this->cfg['app.debug'] = false;
        $this->cfg['app.level'] = 'WARNING';
        foreach ($scopeIn as $sname => $value) {
            $this->cfg[$sname] = $value;
        }
        $this->cfg['app.timezone'] = 'Europe/London';
        // LOGGING PROVIDER
        // intrigante devo ragionare su come gestire l'evento
        $this->register(new settingsProviderModel());
        $this->mcpLogging = new Component\mcpDebugClass($this);
        $this->mcpCore = new Component\mcpCoreClass($this);
        $this->mcpTools = new Component\mcpToolsClass();
        // LEGACY SETTINGS
        $this->legacySetting();
    }

    /**
     * generate the global vars like older system
     * update the data of the Input Array.
     */
    public function legacySetting()
    {
        $this->info('Start Legacy Env');
        $GLOBALS['cfg'] = &$this->cfg;
        $GLOBALS['mcp'] = &$this;
    }

    /////////////////////////////////////////////////////////////////////////////
    // CFG CONTROLLER
    /////////////////////////////////////////////////////////////////////////////

    /**
     * @return string
     */
    public function register(mcpServiceProviderModelClass $service)
    {
        $res = $service->register($this, $this->cfg);
        if ($res instanceof mcpConfigArrayModelClass) {
            $this->cfg = $res;
            // LEGACY SETTINGS
            $this->legacySetting();
        }
    }

    /**
     * @param null $resname
     */
    public function getCfg($resname = null)
    {
        if ($resname == null) {
            return $this->cfg->toArray();
        }
        if (isset($this->cfg[$resname])) {
            if ($this->cfg[$resname] == 'true') {
                return true;
            }
            if ($this->cfg[$resname] == 'false') {
                return false;
            }

            return $this->cfg[$resname];
        }

        return null;
    }

    /**
     * @param $resname name of value
     * @param $revalue values
     *
     * @return bool if operation coplete success true (othervise false)
     */
    public function setCfg($resname, $revalue)
    {
        if ($revalue == '.') {
            if (isset($this->cfg[$resname])) {
                unset($this->cfg[$resname]);
            }
        } else {
            $this->cfg[$resname] = $revalue;
        }
        if ($this->getCfg('app.debug') == true) {
            if ($this->mcpLogging != null) {
                $this->mcpLogging->imhere();
                $this->mcpLogging->info('setCfg:'.$resname);
            }
        }

        return true;
    }

    /////////////////////////////////////////////////////////////////////////////
    // COMMON CONTROLLER
    /////////////////////////////////////////////////////////////////////////////

    /**
     * @param null $resname
     */
    public function getCommon($resname = null)
    {
        if ($resname == null) {
            return $this->common;
        }
        if (isset($this->common[$resname])) {
            if ($this->common[$resname] == 'true') {
                return true;
            }
            if ($this->common[$resname] == 'false') {
                return false;
            }

            return $this->common[$resname];
        }

        return null;
    }

    /**
     * @param $resname name of value
     * @param $revalue values
     *
     * @return bool if operation coplete success true (othervise false)
     */
    public function setCommon($resname, $revalue)
    {
        if ($revalue == '.') {
            if (isset($this->common[$resname])) {
                unset($this->common[$resname]);
            }
        } else {
            $this->common[$resname] = $revalue;
        }

        return true;
    }

    /**
     * updateCommonByEnv
     * load env variable on the common set.
     *
     * @param mixed $setForce
     */
    public function updateCommonByEnv($setForce = false)
    {
        foreach ($_REQUEST as $rk => $rv) {
            if (!isset($this->common[$rk]) || ($setForce == true)) {
                $this->common[$rk] = $rv;
            } elseif (empty($_REQUEST[$rk]) || ($setForce == true)) {
                $this->common[$rk] = $rv;
            }
        }
        foreach ($_GET as $gk => $gv) {
            if (!isset($this->common[$gk]) || ($setForce == true)) {
                $this->common[$gk] = $gv;
            } elseif (empty($_REQUEST[$gk]) || ($setForce == true)) {
                $this->common[$gk] = $gv;
            }
        }
        foreach ($_POST as $pk => $pv) {
            if (!isset($this->common[$pk]) || ($setForce == true)) {
                $this->common[$pk] = $pv;
            } elseif (empty($_REQUEST[$pk]) || ($setForce == true)) {
                $this->common[$pk] = $pv;
            }
        }
    }

    /**
     * RemCommon
     * Display on web comment
     * the common array.
     */
    public function RemCommon()
    {
        $this->mcpLogging->webRem('Common', $this->common);
    }

    /////////////////////////////////////////////////////////////////////////////
    // BUFFER CONTROLLER
    /////////////////////////////////////////////////////////////////////////////

    /**
     * @param null $resname
     */
    public function getBuffer($resname)
    {
        if ($resname == null) {
            return null;
        }
        if (isset($this->buffer[$resname])) {
            return $this->buffer[$resname];
        }

        return null;
    }

    /**
     * @param $resname name of value
     * @param $revalue values
     *
     * @return bool if operation coplete success true (othervise false)
     */
    public function appendBuffer($resname, $resvalue)
    {
        if (!isset($this->buffer[$resname])) {
            $this->buffer[$resname] = array();
        }
        $this->buffer[$resname][] = $resvalue;

        return true;
    }

    /**
     * @param $resname name of value
     * @param $revalue values
     *
     * @return bool if operation coplete success true (othervise false)
     */
    public function updateBuffer($resname, $resindex, $resvalue)
    {
        if (!isset($this->buffer[$resname])) {
            $this->buffer[$resname] = array();
        }
        if (!isset($this->buffer[$resname][$resindex])) {
            $this->buffer[$resname][$resindex] = 0;
        }
        switch ($resvalue) {
        case '++':
            $val = intval($this->buffer[$resname][$resindex]);
            ++$val;
            $this->buffer[$resname][$resindex] = $val;
            break;
        case '--':
            $val = intval($this->buffer[$resname][$resindex]);
            --$val;
            $this->buffer[$resname][$resindex] = $val;
            break;
        default:
            $this->buffer[$resname][$resindex] = $resvalue;
            break;
        }

        return true;
    }

    /**
     * RemBuffer
     * Display on web comment
     * the common array.
     */
    public function RemBuffer($resname)
    {
        if (!isset($this->buffer[$resname])) {
            $this->buffer[$resname] = array();
        }
        $this->mcpLogging->webRem('Buffer:'.$resname, $this->buffer[$resname]);
    }

    /////////////////////////////////////////////////////////////////////////////
    // BENCHMARCK
    /////////////////////////////////////////////////////////////////////////////

    /**
     * getFloatTime function.
     *
     * @return float
     */
    private function getFloatTime()
    {
        list($usec, $sec) = explode(' ', microtime());

        return (float) $usec + (float) $sec;
    }

    /**
     * benchmark function.
     *
     * @param string $stepmessage
     * @param bool   $rem
     *
     * @return float
     */
    public function benchmark($stepmessage, $rem = false)
    {
        $time_end = $this->getFloatTime();
        $time = $time_end - $this->startTime;
        if ($rem == false) {
            $this->debug('['.$time.']:'.$stepmessage);
        } else {
            $this->Rem($stepmessage, $time);
        }

        return $time;
    }

    /////////////////////////////////////////////////////////////////////////////
    // MENU CONTROLLER
    /////////////////////////////////////////////////////////////////////////////

    /**
     * setMenu sequence.
     *
     * @param mixed $name
     * @param mixed $sequence
     */
    public function setMenu($name, array $sequence)
    {
        return $this->setCfg('app.menu.'.$name, $sequence);
    }

    /**
     * setTag sequence.
     *
     * @param mixed $name
     * @param mixed $sequence
     */
    public function setTag($name, array $sequence)
    {
        return $this->setCfg('app.tag.'.$name, $sequence);
    }

    /**
     * load a specific app resource.
     *
     * @param type $resource name ( - "app.")
     *
     * @return any content of specific resource
     */
    public function getResource($resource)
    {
        if (isset($this->cfg['app.'.$resource])) {
            $this->info('CALL DIRECT RESOURCE app.'.$resource.'=Ready');

            return $this->cfg['app.'.$resource];
        }
        $this->info('CALL DIRECT RESOURCE app.'.$resource.'=Null');

        return null;
    }

    /////////////////////////////////////////////////////////////////////////////
    // SCOPE MANAGER
    /////////////////////////////////////////////////////////////////////////////

    /**
     * update the data of the Input Array.
     *
     * @param string $name
     * @param any    $value
     */
    public function setScopeIn($name, $value)
    {
        $this->mcpCore->setScopeIn($name, $value);
    }

    /**
     * update the data of the output Array.
     *
     * @param string $name
     * @param any    $value
     */
    public function setScopeOut($name, $value)
    {
        $this->mcpCore->setScopeOut($name, $value);
    }

    /**
     * update the data of the Control Array.
     *
     * @param string $name
     * @param any    $value
     */
    public function setScopeCtl($name, $value)
    {
        $this->mcpCore->setScopeCtl($name, $value);
    }

    /**
     * return the input array.
     *
     * @return array ()
     */
    public function getScopeIn()
    {
        return $this->mcpCore->getScopeIn();
    }

    /**
     * return the array array.
     *
     * @return array ()
     */
    public function getScopeOut()
    {
        return $this->mcpCore->getScopeOut();
    }

    /**
     * return the array of result (empty array if is null).
     *
     * @return array ()
     */
    public function getScopeOutResult()
    {
        $res = $this->getScopeOut();
        if (isset($res['return'])) {
            return $res['return'];
        }

        return array();
    }

    /**
     * is is valid and true returun the value of the status, for all other case is false.
     *
     * @return bool status
     */
    public function getScopeOutStats()
    {
        $res = $this->getScopeOut();
        if (isset($res['status'])) {
            if ($res['status'] == true) {
                return true;
            }
        }

        return false;
    }

    /**
     * rest output scope varable with out clean historiy and status.
     */
    public function rstScopeOut()
    {
        $this->mcpCore->rstScopeOut();
    }

    /**
     * return the Control array.
     *
     * @return array ()
     */
    public function getScopeCtl()
    {
        return $this->mcpCore->getScopeCtl();
    }

    /**
     * Set status of elaborations.
     *
     * @param bool   $status
     * @param string $message
     */
    public function setStatus($status, $message)
    {
        $this->mcpCore->setStatus($status, $message);
    }

    /**
     * Set Te actuos IPL area is working.
     *
     * @param type $area
     */
    public function setIpl($area)
    {
        $this->mcpCore->setWorkingArea($area);
    }

    /////////////////////////////////////////////////////////////////////////////
    // TRANSLATE AREA
    /////////////////////////////////////////////////////////////////////////////

    /**
     * translate.
     *
     * @param string $message
     *
     * @return string translation
     */
    public function translate($message)
    {
        return mcpLanguageClass::translate($message);
    }

    /**
     * translateMulti.
     *
     * @param string $lang
     * @param string $message
     *
     * @return string translation
     */
    public function translateMulti($lang, $message)
    {
        return mcpLanguageClass::multiTranslate($lang, $message);
    }

    /////////////////////////////////////////////////////////////////////////////
    // DEBUGGING AREA
    /////////////////////////////////////////////////////////////////////////////

    /**
     * isDebug() check if the cfg params app.debug is true
     * for the other case remain false.
     *
     * @return bool
     */
    public function isDebug()
    {
        if ($this->getCfg('app.debug') == 'true') {
            return true;
        }

        return false;
    }

    /**
     * debug class (level debug).
     *
     * @param string $message
     */
    public function debug($message)
    {
        if ($this->getCfg('app.debug') == 'true') {
            if ($this->mcpLogging != null) {
                $this->mcpLogging->debug($message);
            }
        }
    }

    /**
     * debug class (level debug).
     *
     * @param string $message
     * @param type   $name    value name;
     * @param type   $value   value content
     */
    public function debugVar($message, $name, $value)
    {
        $this->debug($message.':'.$name.'='.print_r($value, 1));
    }

    /**
     * debug class (level notice/info).
     *
     * @param string $message
     */
    public function info($message)
    {
        if ($this->mcpLogging != null) {
            $this->mcpLogging->info($message);
        }
    }

    /**
     * debug class (level notice/info).
     *
     * @param string $message
     */
    public function imhere()
    {
        if ($this->getCfg('app.debug') == true) {
            $this->mcpLogging->imhere();
        }
    }

    /**
     * debug class (level warning).
     *
     * @param string $message
     */
    public function warning($message)
    {
        $this->mcpLogging->warning($message);
    }

    /**
     * debug class (level error).
     *
     * @param string $message
     */
    public function error($message)
    {
        $this->mcpLogging->error($message);
    }

    /**
     * debug class (level critical)
     * send a debug message to support.
     *
     * @param string $message
     */
    public function supportmail($message)
    {
        mcpMailClass::supportmail($message);
    }

    /**
     * debug class (level critical and die).
     *
     * @param string $message
     */
    public function critical($message)
    {
        $this->mcpLogging->critical($message);
    }

    /**
     * not found page.
     *
     * @param string $message
     */
    public function notFound($message)
    {
        $this->mcpLogging->notFound($message);
    }

    /**
     * Make a Web Rem  with this message.
     *
     * @param string $message
     * @param string $var
     */
    public function Rem($message, $var = null)
    {
        $this->mcpLogging->webRem($message, $var);
    }

    /**
     * Make a script array  with this name.
     *
     * @param string $message
     * @param string $var
     */
    public function toJavascript($message, array $scopeIn)
    {
        $this->mcpLogging->jsDumpScript($message, $scopeIn);
    }

    /**
     * Make a script array  with this name.
     *
     * @param string $message
     * @param string $var
     */
    public function toJson(array $scopeIn)
    {
        $this->mcpLogging->jsonDump($scopeIn);
    }

    /**
     * Make a Web Rem  with this message.
     *
     * @param string $message
     * @param string $var
     */
    public function DebugRem($message, $var = null)
    {
        $this->mcpLogging->webDebugRem($message, $var);
    }

    /**
     * Make a Web dumo with html tag of with this message and var.
     *
     * @param string $message
     */
    public function display($message, $var = null)
    {
        $this->mcpLogging->webDump($message, $var);
    }

    /////////////////////////////////////////////////////////////////////////////
    // SPECIAL FUNCTION
    /////////////////////////////////////////////////////////////////////////////

    /**
     * move to php file and close if need.
     *
     * @param dest dest phpfile
     * @param default if not exist use this phpfile
     * @param ext  ".php" or more if need to add to $dest and $default
     * @param path  if is different to the system path
     * @param andEnd (def true) if neet to exit at end of call
     */
    public function move($dest, $default = null, $ext = '', $path = null, $andEnd = true)
    {
        $this->mcpTools->move($dest, $default, $ext, $path, $andEnd);
    }

    /**
     * header redirect and more.
     *
     * @param string $string  rules
     * @param bool   $end     die after change
     * @param bool   $replace remplace header
     * @param int    $retcode html return code if need
     */
    public function header($string, $end = false, $replace = true, $retcode = null, $htmljs = false)
    {
        $this->mcpTools->header($string, $end, $replace, $retcode, $htmljs);
    }

    /**
     * Clear String from Escape chars (mcpTools).
     *
     * @param string $string
     *
     * @return string
     */
    public function escapeClear($string)
    {
        return $this->mcpTools->escapeClear($string);
    }

    /**
     * convert String to a standard ascii (mcpTools).
     *
     * @param string $string
     *
     * @return string
     */
    public function ConvertToAscii($string)
    {
        return $this->mcpTools->toAscii($string);
    }

    /**
     * Request save to session.
     *
     * @param type $arguments name of the request
     * @param type $onlyPost  if true don-t read get
     */
    public function Req2Session($arguments, $onlyPost = false)
    {
        return $this->mcpTools->Req2Session($arguments, $onlyPost);
    }

    /**
     * Request save to session.
     *
     * @param type $object to conver
     *
     * @return array of the object
     */
    public function Object2Array($object)
    {
        return $this->mcpTools->Object2Array($object);
    }

    /**
     *  clean the cache if is active.
     */
    public function flushCache()
    {
        if (isset($GLOBALS['cfg']['app.cache'])) {
            $GLOBALS['cfg']['app.cache']->flush();
        }
        if (isset($GLOBALS['cfg']['app.pdo.cache'])) {
            $GLOBALS['cfg']['app.pdo.cache']->flush();
        }
        if (isset($_SESSION)) {
            $_SESSION['pdo.cache'] = array();
        }
        if (isset($GLOBALS['pdo.cache'])) {
            $GLOBALS['pdo.cache'] = array();
        }
    }

    /////////////////////////////////////////////////////////////////////////////
    // MODULE CONTROLLER
    /////////////////////////////////////////////////////////////////////////////

    /**
     * @param string $path
     * @param string $callname
     * @param bool   $ispreload
     * @param array  $scopeIn
     * @param string $modinit
     * @param string $subcall
     * @param string $vendor
     * @param string $type
     */
    public function statmentModule($path, $callname, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null, $type = null)
    {
        $this->mcpCore->statmentModule($path, $callname, $ispreload, $scopeIn, $modinit, $subcall, $vendor, $type);
    }

    /**
     * Load a module or a template and clear the vars.
     */
    public function loadModule()
    {
        $this->mcpCore->moduleLoader();
    }

    /**
     * Load a module or a template and clear the vars.
     */
    public function loadLegacy()
    {
        $res = $this->mcpCore->loadLegacy();
        if (isset($res['return'])) {
            return $res['return'];
        }

        return $res;
    }

    /**
     *  load and execute module and clear the vars after results.
     *
     * @return array results
     */
    public function callModule()
    {
        return $this->mcpCore->moduleCaller();
    }

    /**
     * @param string $callname  name of the functionality
     * @param string $path      path where present the basedirectory of the data
     * @param bool   $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array  $scopeIn   Input Array with the value need to work
     * @param string $modinit   Module name where is present the code and be load and initalized
     * @param string $subcall   used if the name of the functionality ($callname) and the subcall are different
     * @param string $vendor    this code is part of specific vendor (ex ft )
     * @param string $type      is a Page, Block, Controller, Driver
     *
     * @return array $ScopeOut
     */
    public function module($callname, $path = null, $ispreload = false, array $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null, $type = null)
    {
        if ($path == null) {
            $path = $this->pathsrc;
        }
        $this->statmentModule($path, $callname, $ispreload, $scopeIn, $modinit, $subcall, $vendor, $type);

        return $this->callModule();
    }

    /**
     * @param string $callname  name of the functionality
     * @param string $path      path where present the basedirectory of the data
     * @param bool   $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array  $scopeIn   Input Array with the value need to work
     * @param string $modinit   Module name where is present the code and be load and initalized
     * @param string $subcall   used if the name of the functionality ($callname) and the subcall are different
     * @param string $vendor    this code is part of specific vendor (ex ft )
     * @param string $type      is a Page, Block, Controller, Driver
     * @param bool   $hasreturn if is called the objet return the value as string
     *
     * @return string output
     */
    public function template($callname, $path = null, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null, $type = null, $hasreturn = false)
    {
        if ($path == null) {
            $path = $this->pathtpl;
        }

        return mcpTemplateClass::template($callname, $path, $ispreload, $scopeIn, $modinit, $subcall, $vendor, $type, $hasreturn);
    }

    /**
     *  similar with module but at end exit (0 okdone - 1 with error ).
     *
     * @param string $callname  name of the functionality
     * @param string $path      path where present the basedirectory of the data
     * @param bool   $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array  $scopeIn   Input Array with the value need to work
     * @param string $modinit   Module name where is present the code and be load and initalized
     * @param string $subcall   used if the name of the functionality ($callname) and the subcall are different
     * @param string $vendor    this code is part of specific vendor (ex ft )
     * @param string $type      is a Page, Block, Controller, Driver
     *
     * @return array $ScopeOut
     */
    public function moduleGoTo($callname, $path = null, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null, $type = null)
    {
        $res = 0;
        if (empty($callname)) {
            $this->critical('Moving to Null Error');
        }
        $scopeOut = module($callname, $path = null, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null, $type = null);
        if (isset($scopeOut['status'])) {
            if ($scopeOut['status'] == false) {
                $res = 1;
            }
        }
        exit($res);
    }

    /////////////////////////////////////////////////////////////////////////////
    // MODULE CALL
    /////////////////////////////////////////////////////////////////////////////

    /**
     * similar to module but to easy.
     *
     * @param string $libname name of the functionality
     * @param array  $scopeIn Input Array with the value need to work
     *
     * @return array $ScopeOut
     */
    public function moduleLoad($libname, $module, $vendor, $scopeIn = array())
    {
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }

        return $this->module($libname, $this->pathsrc, true, $scopeIn, $module, null, $vendor);
    }

    /**
     * similar to module but to easy.
     *
     * @param string $libname name of the functionality
     * @param array  $scopeIn Input Array with the value need to work
     *
     * @return array $ScopeOut
     */
    public function moduleRun($libname, $scopeIn = array())
    {
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }

        return $this->module($libname, $this->pathsrc, false, $scopeIn);
    }

    /**
     * legacy class loader
     * rember the class need to not have required args on constructor.
     *
     * @param string $callname name of the functionality
     * @param array  $scopeIn  Input Array with the value need to work
     * @param string $modinit  Module name where is present the code and be load and initalized
     * @param string $subcall  used if the name of the functionality ($callname) and the subcall are different
     * @param string $vendor   this code is part of specific vendor (ex ft )
     * @param string $type     is a Page, Block, Controller, Driver
     * @param string $path     path where present the basedirectory of the data
     *
     * @return array $ScopeOut
     */
    public function legacyClass($callname, array $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null, $path = null)
    {
        if ($path == null) {
            $path = $this->pathsrc;
        }
        $this->statmentModule($path, $callname, true, $scopeIn, $modinit, $subcall, $vendor, 'Legacy');

        return $this->loadLegacy();
    }

    /**
     * Remote calling.
     *
     * @param string $ctrlproc name of the driver
     * @param array  $scopeIn  Input Array with the value need to work
     * @param string $modinit  Module name where is present the code and be load and initalized
     * @param string $subcall  used if the name of the functionality ($callname) and the subcall are different
     *
     * @return array $ScopeOut
     */
    public function remote($ctrlproc, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null)
    {
        $this->info('MCP>>Remote>>'.$ctrlproc);
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }

        return mcpProxyClass::Remote($this, $ctrlproc, $scopeIn, $modinit, $subcall, $vendor);
    }

    /**
     * Run Shell.
     *
     * @param string $ctrlproc name of the driver
     * @param array  $scopeIn  Input Array with the value need to work
     * @param string $modinit  Module name where is present the code and be load and initalized
     * @param string $subcall  used if the name of the functionality ($callname) and the subcall are different
     *
     * @return array $ScopeOut
     */
    public function shell($ctrlproc, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null)
    {
        $this->info('MCP>>Shell>>'.$ctrlproc);
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }

        return mcpProxyClass::Shell($this, $ctrlproc, $scopeIn, $modinit, $subcall, $vendor);
    }

    /**
     * Run Module as Driver.
     *
     * @param string $libname   name of the driver
     * @param bool   $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array  $scopeIn   Input Array with the value need to work
     * @param string $modinit   Module name where is present the code and be load and initalized
     * @param string $subcall   used if the name of the functionality ($callname) and the subcall are different
     * @param string $subcall   used if the name of the functionality ($callname) and the subcall are different
     * @param string $vendor    this code is part of specific vendor (ex ft )
     * @param string $path      path where present the basedirectory of the data
     *
     * @return array $ScopeOut
     */
    public function driver($libname, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null, $path = null)
    {
        if ($path == null) {
            $path = $this->pathsrc;
        }
        if ($vendor == null) {
            $vendor = $this->defvnd;
        }
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }
        $this->info('MCP>>'.$vendor.'>>Driver>>'.$libname);

        return $this->module($libname, $path, $ispreload, $scopeIn, $modinit, $subcall, $vendor, 'Driver');
    }

    /**
     * Run Module as database query.
     *
     * @param string $dbproc    name of the driver
     * @param bool   $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array  $scopeIn   Input Array with the value need to work
     * @param string $modinit   Module name where is present the code and be load and initalized
     * @param string $subcall   used if the name of the functionality ($callname) and the subcall are different
     *
     * @return array $ScopeOut
     */
    public function query($dbproc, $ispreload = true, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null)
    {
        if ($vendor == null) {
            $vendor = $this->defapp;
        }
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }
        $this->info('MCP>>'.$vendor.'>>query>>'.$dbproc);

        return $this->module($dbproc, $this->pathsrc, $ispreload, $scopeIn, $modinit, $subcall, $vendor, 'Query');
    }

    /**
     * Run Module as database query.
     *
     * @param string $dbproc    name of the driver
     * @param bool   $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array  $scopeIn   Input Array with the value need to work
     * @param string $modinit   Module name where is present the code and be load and initalized
     * @param string $subcall   used if the name of the functionality ($callname) and the subcall are different
     *
     * @return array $ScopeOut
     */
    public function queryR($dbproc, $ispreload = true, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null)
    {
        if ($vendor == null) {
            $vendor = $this->defapp;
        }
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }
        $this->info('MCP>>'.$vendor.'>>query[R]>>'.$dbproc);
        $res = $this->module($dbproc, $this->pathsrc, $ispreload, $scopeIn, $modinit, $subcall, $vendor, 'Query');

        return $res['return'];
    }

    /**
     * Run Module as database query common intenal.
     *
     * @param string $dbproc    name of the driver by default json
     * @param bool   $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array  $scopeIn   Input Array with the value need to work
     * @param string $modinit   Module name where is present the code and be load and initalized by default Pdo
     * @param string $subcall   used if the name of the functionality ($callname) and the subcall are different
     *
     * @return array $ScopeOut
     */
    public function queryCommonR($dbproc = 'Json', $ispreload = true, $scopeIn = array(), $modinit = 'Pdo', $subcall = null)
    {
        $this->info('MCP>>'.$this->defapp.'>>query[R]>>'.$dbproc);
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }
        $res = $this->module($dbproc, $this->pathsrc, $ispreload, $scopeIn, $modinit, $subcall, $this->defvnd, 'Query');

        return $res['return'];
    }

    /**
     * Run Module as database query by json file.
     *
     * @param string $dbproc  name of the driver by default json
     * @param array  $scopeIn Input Array with the value need to work
     * @param string $modinit Module name where is present the code and be load and initalized by default Pdo
     * @param string $path    path where present the basedirectory of the data
     *
     * @return array $ScopeOut
     */
    public function queryJsonR($dbprc, $scopeIn = array(), $modinit = null, $vendor = null, $path = null)
    {
        $this->info('MCP>>'.$this->defapp.'>>query[J]>>'.$dbprc);
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }
        if ($vendor != null) {
            // if vendor is select all this are setted
            $scopeIn['P'] = $this->pathsrc;
            $scopeIn['M'] = $dbprc;
            $scopeIn['V'] = $vendor;
        }
        if ($modinit != null) {
            $scopeIn['P'] = $this->pathsrc;
            $scopeIn['M'] = $modinit;
        }
        if ($path != null) {
            $scopeIn['P'] = $path;
        }
        $scopeIn['J'] = $dbprc;

        return $this->queryCommonR('Json', false, $scopeIn, 'Pdo');
    }

    /**
     * Run Module as database query by Array file.
     *
     * @param string $dbproc  name of the driver by default json
     * @param array  $scopeIn Input Array with the value need to work
     * @param string $modinit Module name where is present the code and be load and initalized by default Pdo
     * @param string $path    path where present the basedirectory of the data
     *
     * @return array $ScopeOut
     */
    public function queryArrayR($scopeIn = array())
    {
        $this->info('MCP>>'.$this->defapp.'>>query[A]>>');

        return $this->queryCommonR('Array', false, $scopeIn, 'Pdo');
    }

    /**
     * Run Module as controller.
     *
     * @param string $ctrlproc  name of the driver
     * @param bool   $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array  $scopeIn   Input Array with the value need to work
     * @param string $modinit   Module name where is present the code and be load and initalized
     * @param string $subcall   used if the name of the functionality ($callname) and the subcall are different
     *
     * @return array $ScopeOut
     */
    public function controller($ctrlproc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null)
    {
        if ($vendor == null) {
            $vendor = $this->defapp;
        }
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }
        $this->info('MCP>>'.$vendor.'>>controller>>'.$ctrlproc);

        return $this->module($ctrlproc, $this->pathsrc, $ispreload, $scopeIn, $modinit, $subcall, $vendor, 'Controller');
    }

    /**
     * Run Module as controller only return.
     *
     * @param string $ctrlproc  name of the driver
     * @param bool   $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array  $scopeIn   Input Array with the value need to work
     * @param string $modinit   Module name where is present the code and be load and initalized
     * @param string $subcall   used if the name of the functionality ($callname) and the subcall are different
     *
     * @return mixed|bool $ScopeOut["return"]
     */
    public function controllerR($ctrlproc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null)
    {
        $res = $this->controller($ctrlproc, $ispreload, $scopeIn, $modinit, $subcall, $vendor);
        if (isset($res['return'])) {
            return $res['return'];
        }

        return false;
    }

    /**
     * Run Module as controller as common for all.
     *
     * @param string $ctrlproc  name of the driver
     * @param bool   $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array  $scopeIn   Input Array with the value need to work
     * @param string $modinit   Module name where is present the code and be load and initalized
     * @param string $subcall   used if the name of the functionality ($callname) and the subcall are different
     *
     * @return array $ScopeOut
     */
    public function controllerCommon($ctrlproc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null)
    {
        $this->info('MCP>>controller(C)>>'.$ctrlproc);
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }

        return $this->module($ctrlproc, $this->pathmcp, $ispreload, $scopeIn, $modinit, $subcall, $this->defvnd, 'Controller');
    }

    /**
     * Run Module as controller as common for all only the return.
     *
     * @param string $ctrlproc  name of the driver
     * @param bool   $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array  $scopeIn   Input Array with the value need to work
     * @param string $modinit   Module name where is present the code and be load and initalized
     * @param string $subcall   used if the name of the functionality ($callname) and the subcall are different
     *
     * @return mixed|bool $ScopeOut["return"]
     */
    public function controllerCommonR($ctrlproc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null)
    {
        $res = $this->controllerCommon($ctrlproc, $ispreload, $scopeIn, $modinit, $subcall);
        if (isset($res['return'])) {
            return $res['return'];
        }

        return false;
    }

    /**
     * Run Module as ToolApi Components.
     *
     * @param string $srvprc    name of the driver
     * @param bool   $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array  $scopeIn   Input Array with the value need to work
     * @param string $modinit   Module name where is present the code and be load and initalized
     * @param string $subcall   used if the name of the functionality ($callname) and the subcall are different
     */
    public function api($srvprc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null, $mcptype = 'Api')
    {
        mcpApiClass::api($this, $srvprc, $ispreload, $scopeIn, $modinit, $subcall, $vendor, $mcptype);
    }

    /**
     * Run Module as ToolApi Components.
     *
     * @param string $srvprc    name of the driver
     * @param bool   $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array  $scopeIn   Input Array with the value need to work
     * @param string $modinit   Module name where is present the code and be load and initalized
     * @param string $subcall   used if the name of the functionality ($callname) and the subcall are different
     */
    public function apiR($srvprc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null, $mcptype = 'Api')
    {
        mcpApiClass::apiReturn($this, $srvprc, $ispreload, $scopeIn, $modinit, $subcall, $vendor, $mcptype);
    }

    /**
     * Run Module as ToolApi Components.
     *
     * @param string $srvprc    name of the driver
     * @param bool   $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array  $scopeIn   Input Array with the value need to work
     * @param string $modinit   Module name where is present the code and be load and initalized
     * @param string $subcall   used if the name of the functionality ($callname) and the subcall are different
     *
     * @return array $scopeOut;
     */
    public function apiA($srvprc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null, $mcptype = 'Api')
    {
        mcpApiClass::apiArray($this, $srvprc, $ispreload, $scopeIn, $modinit, $subcall, $vendor, $mcptype);
    }

    /**
     * Run Module as ToolApi Components.
     *
     * @param string $srvprc    name of the driver
     * @param bool   $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array  $scopeIn   Input Array with the value need to work
     * @param streturnng $modinit   Module name where is present the code and be load and initalized
     * @param streturnng $subcall   used if the name of the functionality ($callname) and the subcall are different
     */
    public function apiCommon($srvprc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $mcptype = 'Api')
    {
        mcpApiClreturns::apiCommon($this, $srvprc, $ispreload, $scopeIn, $modinit, $subcall, null, $mcptype);
    }

    /**
     * Run Modulreturnas ToolApi Components.
     *
     * @param string $srvprc    name of the driver
     * @param bool   $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array  $scopeIn   Input Array with the value need to work
     * @param string $modinit   Module name where is present the code and be load and initalized
     * @param string $subcall   used if the name of the functionality ($callname) and the subcall are different
     */
    public function apiACommon($srvprc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $mcptype = 'Api')
    {
        mcpApiClass::apiCommonArray($this, $srvprc, $ispreload, $scopeIn, $modinit, $subcall, null, $mcptype);
    }

    /**
     * Run Module as service.
     *
     * @param string $srvprc    name of the driver
     * @param bool   $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array  $scopeIn   Input Array with the value need to work
     * @param string $modinit   Module name where is present the code and be load and initalized
     * @param string $subcall   used if the name of the functionality ($callname) and the subcall are different
     *
     * @return array $ScopeOut
     */
    public function service($srvprc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null)
    {
        if ($vendor == null) {
            $vendor = $this->defapp;
        }
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }
        $this->info('MCP>>'.$vendor.'>>service>>'.$srvprc);

        return $this->module($srvprc, $this->pathsrc, $ispreload, $scopeIn, $modinit, $subcall, $vendor, 'Service');
    }

    /**
     * Run Module as service Return.
     *
     * @param string $srvprc    name of the driver
     * @param bool   $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array  $scopeIn   Input Array with the value need to work
     * @param string $modinit   Module name where is present the code and be load and initalized
     * @param string $subcall   used if the name of the functionality ($callname) and the subcall are different
     *
     * @return array $ScopeOut
     */
    public function serviceR($srvprc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null)
    {
        if ($vendor == null) {
            $vendor = $this->defapp;
        }
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }
        $this->info('MCP>>'.$vendor.'>>service(R)>>'.$srvprc);
        $res= $this->module($srvprc, $this->pathsrc, $ispreload, $scopeIn, $modinit, $subcall, $vendor, 'Service');
        if (isset($res["return"])) {
            return $res["return"];
        }
        return false;
    }

    /**
     * Run Module as service.
     *
     * @param string $srvprc    name of the driver
     * @param bool   $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array  $scopeIn   Input Array with the value need to work
     * @param string $modinit   Module name where is present the code and be load and initalized
     * @param string $subcall   used if the name of the functionality ($callname) and the subcall are different
     *
     * @return array $ScopeOut
     */
    public function serviceCommon($srvprc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null)
    {
        $this->info('MCP>>service(C)>>'.$srvprc);
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }

        return $this->module($srvprc, $this->pathmcp, $ispreload, $scopeIn, $modinit, $subcall, $this->defvnd, 'Service');
    }

   /**
     * Run Module as service Return
     *
     * @param string $srvprc    name of the driver
     * @param bool   $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array  $scopeIn   Input Array with the value need to work
     * @param string $modinit   Module name where is present the code and be load and initalized
     * @param string $subcall   used if the name of the functionality ($callname) and the subcall are different
     *
     * @return array $ScopeOut
     */
    public function serviceCommonR($srvprc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null)
    {
        $this->info('MCP>>service(C)>>'.$srvprc);
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }
        $res= $this->module($srvprc, $this->pathmcp, $ispreload, $scopeIn, $modinit, $subcall, $this->defvnd, 'Service');
        if (isset($res["return"])) {
            return $res["return"];
        }
        return false;
    }

    /**
     * mail
     * where $scopeIn :
     * - to :
     * - from :
     * - subject :
     * - message :
     * - files :.
     *
     * @param mixed $page
     * @param mixed $scopeIn
     * @param mixed $modinit
     */
    public function mail($page = 'sendmail', $scopeIn = array(), $modinit = null, $vendor = null)
    {
        $this->info('MCP>>mail>>'.$page);
        if (!is_array($scopeIn)) {
            return null;
        }

        return mcpMailClass::mailService($page, $scopeIn, $modinit, $vendor);
    }

    /////////////////////////////////////////////////////////////////////////////
    // PAGE TEMPLATE / VIEW
    /////////////////////////////////////////////////////////////////////////////

    /**
     * Load a page with your ScopeIn.
     *
     * @param string $page    name of the Page
     * @param array  $scopeIn Input Array with the value need to work
     * @param string $modinit Module name where is present the code and be load and initalized
     *
     * @return string output (if is true return flag)
     */
    public function page($page, $scopeIn = array(), $modinit = null, $vendor = null, $pathtpl = null, $hasreturn = false)
    {
        if ($vendor == null) {
            $vendor = $this->defapp;
        }
        if ($pathtpl == null) {
            $pathtpl = $this->pathtpl;
        }
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }
        $scopeIn['prev-output'] = ob_get_clean();
        $this->info('MCP>>'.$vendor.'>>page>>'.$page);
        $this->RunEvent('page_start_'.$page);
        $ret = $this->template($page, $pathtpl, true, $scopeIn, $modinit, null, $vendor, 'Page', $hasreturn);
        $this->RunEvent('page_stop_'.$page);

        return $ret;
    }

    /////////////////////////////////////////////////////////////////////////////
    // BLOCK TEMPLATE / VIEW
    /////////////////////////////////////////////////////////////////////////////

    /**
     * Load a Block with your ScopeIn.
     *
     * @param string $block   name of the Block
     * @param array  $scopeIn Input Array with the value need to work
     * @param string $modinit Module name where is present the code and be load and initalized
     *
     * @return string output (if is true return flag)
     */
    public function render($block, $scopeIn = array(), $modinit = null, $vendor = null, $pathsrc = null, $hasreturn = false)
    {
        if ($vendor == null) {
            $vendor = $this->defapp;
        }
        if ($pathsrc == null) {
            $pathsrc = $this->pathsrc;
        }
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }
        $this->info('MCP>>'.$vendor.'>>Render>>'.$block);
        $this->RunEvent('block_start_'.$block);
        $ret = $this->template($block, $pathsrc, true, $scopeIn, $modinit, null, $vendor, 'Render', $hasreturn);
        $this->RunEvent('block_stop_'.$block);

        return $ret;
    }

    /**
     * Load a Block with your ScopeIn.
     *
     * @param string $block   name of the Block
     * @param array  $scopeIn Input Array with the value need to work
     * @param string $modinit Module name where is present the code and be load and initalized
     *
     * @return string output (if is true return flag)
     */
    public function renderCommon($block, $scopeIn = array(), $modinit = null, $vendor = null, $pathsrc = null, $hasreturn = false)
    {
        if ($vendor == null) {
            $vendor = $this->defapp;
        }
        if ($pathsrc == null) {
            $pathsrc = $this->pathmcp;
        }
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }
        $this->info('MCP>>'.$vendor.'>>Render>>'.$block);
        $this->RunEvent('block_start_'.$block);
        $ret = $this->template($block, $pathsrc, true, $scopeIn, $modinit, null, $vendor, 'Render', $hasreturn);
        $this->RunEvent('block_stop_'.$block);

        return $ret;
    }

    /**
     * Load a Block with your ScopeIn.
     *
     * @param string $block   name of the Block
     * @param array  $scopeIn Input Array with the value need to work
     * @param string $modinit Module name where is present the code and be load and initalized
     *
     * @return string output (if is true return flag)
     */
    public function block($block, $scopeIn = array(), $modinit = null, $vendor = null, $pathtpl = null, $hasreturn = false)
    {
        if ($vendor == null) {
            $vendor = $this->defapp;
        }
        if ($pathtpl == null) {
            $pathtpl = $this->pathtpl;
        }
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }
        $this->info('MCP>>'.$vendor.'>>block>>'.$block);
        $this->RunEvent('block_start_'.$block);
        $ret = $this->template($block, $pathtpl, true, $scopeIn, $modinit, null, $vendor, 'Block', $hasreturn);
        $this->RunEvent('block_stop_'.$block);

        return $ret;
    }

    /**
     * Load a Block with your ScopeIn.
     *
     * @param string $block   name of the Block
     * @param array  $scopeIn Input Array with the value need to work
     * @param string $modinit Module name where is present the code and be load and initalized
     *
     * @return string output (if is true return flag)
     */
    public function blockCommon($block, $scopeIn = array(), $modinit = null, $hasreturn = false)
    {
        $this->info('MCP>>block(C)>>'.$block);
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }
        $this->RunEvent('blockCommon_start_'.$block);
        $ret = $this->template($block, $this->pathmcp, true, $scopeIn, $modinit, null, $this->defapp, 'Block', $hasreturn);
        $this->RunEvent('blockCommon_stop_'.$block);

        return $ret;
    }

    /**
     * Load a block with your ScopeIn.
     *
     * @param string $page    name of the Page
     * @param array  $scopeIn Input Array with the value need to work
     * @param string $modinit Module name where is present the code and be load and initalized
     */
    public function blockRemote($page, $scopeIn = array(), $modinit = null, $vendor = null, $hasreturn = false)
    {
        $this->info('MCP>>'.$vendor.'>>block(Remote)>>'.$page);
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }
        if ($vendor == null) {
            $vendor = $this->defapp;
        }
        $this->RunEvent('blockRemote_start_'.$page);
        $ret = mcpProxyClass::apiRemote($this, $page, $scopeIn, $modinit, null, $vendor);
        $this->RunEvent('blockRemote_stop_'.$page);
        if ($hasreturn == true) {
            return $ret;
        } else {
            echo $ret;
        }
    }

    /**
     * Load a block with your ScopeIn.
     *
     * @param string $page    name of the Page
     * @param array  $scopeIn Input Array with the value need to work
     * @param string $modinit Module name where is present the code and be load and initalized
     */
    public function blockShell($page, $scopeIn = array(), $modinit = null, $vendor = null, $hasreturn = false)
    {
        $this->info('MCP>>'.$vendor.'>>block(Shell)>>'.$page);
        if ($vendor == null) {
            $vendor = $this->defapp;
        }
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }
        $this->RunEvent('blockShell_start_'.$page);
        $ret = mcpProxyClass::blockShell($this, $page, $scopeIn, $modinit, null, $vendor);
        $this->RunEvent('blockShell_stop_'.$page);
        if ($hasreturn == true) {
            return $ret;
        } else {
            echo $ret;
        }
    }

    /**
     * Load a mail with your ScopeIn.
     *
     * @param string $page    name of the Page
     * @param array  $scopeIn Input Array with the value need to work
     * @param string $modinit Module name where is present the code and be load and initalized
     */
    /////////////////////////////////////////////////////////////////////////////
    // MODULE CONTROLLER COMPLEX (MVC)
    /////////////////////////////////////////////////////////////////////////////

    /**
     * Run Controller and then load a page with your ScopeIn.
     *
     * @param string $block    name of the Block and the controller
     * @param array  $scopeIn  Input Array with the value need to work
     * @param string $modinit  Module name where is present the code and be load and initalized
     * @param string $pageinit Moduletestmail name if is different for the page
     */
    public function showPage($block, $scopeIn = array(), $modinit = null, $pageinit = null)
    {
        $this->info('MCP>>showPage>>'.$block);
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }
        $this->mcpCore->setClearFlagOff();
        $this->controller($block, false, $scopeIn, $modinit);
        $scopePageIn = $this->getScopeOutResult();
        $this->mcpCore->setClearFlagOn();
        if ($pageinit == null) {
            $pageinit = $modinit;
        }
        $this->page($block, $scopePageIn, $pageinit);
    }

    /**
     * Run Controller and then load a page with your ScopeIn.
     *
     * @param string $block    name of the Block and the controller
     * @param array  $scopeIn  Input Array with the value need to work
     * @param string $modinit  Module name where is present the code and be load and initalized
     * @param string $pageinit Module name if is different for the page
     */
    public function showCommonPage($block, $scopeIn = array(), $modinit = null, $pageinit = null)
    {
        $this->info('MCP>>showCommonPage>>'.$block);
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }
        $this->mcpCore->setClearFlagOff();
        $this->controllerCommon($block, false, $scopeIn, $modinit);
        $scopePageIn = $this->getScopeOutResult();
        $this->mcpCore->setClearFlagOn();
        if ($pageinit == null) {
            $pageinit = $modinit;
        }
        $this->page($block, $scopePageIn, $pageinit, $this->defvnd, $this->pathmcp);
    }

    /**
     * Run Controller and then load a block with your ScopeIn.
     *
     * @param string $block            name of the Block and the controller
     * @param array  $scopeIn          Input Array with the value need to work
     * @param string $controllerModule Module name where is present the code and be load and initalized
     * @param string $blockModule      Module name if is different for the page
     */
    public function showBlock($block, $scopeIn = array(), $controllerModule = null, $blockModule = null)
    {
        $this->info('MCP>>showBlock>>'.$block);
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }
        $this->mcpCore->setClearFlagOff();
        $CtrlOut = $this->controller($block, false, $scopeIn, $controllerModule);
        $scopeCtl = $this->getScopeCtl();
        $this->mcpCore->setClearFlagOn();
        if ($blockModule == null) {
            $blockModule = $controllerModule;
        }
        $sb = true;
        if (isset($scopeCtl['showBlock'])) {
            if ($scopeCtl['showBlock'] == false) {
                $sb = false;
            }
        }
        if (isset($scopeCtl['changeBlock'])) {
            $block = $scopeCtl['changeBlock'];
        }
        if ($sb == true) {
            return $this->block($block, $CtrlOut, $blockModule);
        }
    }

    /**
     * Run Controller and then load a block with your ScopeIn.
     *
     * @param string $block            name of the Block and the controller
     * @param array  $scopeIn          Input Array with the value need to work
     * @param string $controllerModule Module name where is present the code and be load and initalized
     * @param string $blockModule      Module name if is different for the page
     */
    public function showCommonBlock($block, $scopeIn = array(), $controllerModule = null, $blockModule = null)
    {
        $this->info('MCP>>showBlock>>'.$block);
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }
        $this->mcpCore->setClearFlagOff();
        $CtrlOut = $this->controllerCommon($block, false, $scopeIn, $controllerModule);
        $scopeCtl = $this->getScopeCtl();
        $this->mcpCore->setClearFlagOn();
        if ($blockModule == null) {
            $blockModule = $controllerModule;
        }
        $sb = true;
        if (isset($scopeCtl['showBlock'])) {
            if ($scopeCtl['showBlock'] == false) {
                $sb = false;
            }
        }
        if (isset($scopeCtl['changeBlock'])) {
            $block = $scopeCtl['changeBlock'];
        }
        if ($sb == true) {
            return $this->block($block, $CtrlOut, $blockModule);
        }
    }

    /**
     * Run Controller and then load a block with your ScopeIn.
     *
     * @param string $block            name of the Block and the controller
     * @param array  $scopeIn          Input Array with the value need to work
     * @param string $controllerModule Module name where is present the code and be load and initalized
     * @param string $blockModule      Module name if is different for the page
     */
    public function showFullCommonBlock($block, $scopeIn = array(), $controllerModule = null, $blockModule = null)
    {
        $this->info('MCP>>showBlock>>'.$block);
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
        }
        $this->mcpCore->setClearFlagOff();
        $CtrlOut = $this->controllerCommon($block, false, $scopeIn, $controllerModule);
        $scopeCtl = $this->getScopeCtl();
        $this->mcpCore->setClearFlagOn();
        if ($blockModule == null) {
            $blockModule = $controllerModule;
        }
        $sb = true;
        if (isset($scopeCtl['showBlock'])) {
            if ($scopeCtl['showBlock'] == false) {
                $sb = false;
            }
        }
        if (isset($scopeCtl['changeBlock'])) {
            $block = $scopeCtl['changeBlock'];
        }
        if ($sb == true) {
            return $this->blockCommon($block, $CtrlOut, $blockModule);
        }
    }

    /////////////////////////////////////////////////////////////////////////////
    // ARRAY CALLER MODULE
    /////////////////////////////////////////////////////////////////////////////

    /**
     * Run a command inside $scopeCtl.
     *
     * @param mixed $scopectl
     * @param mixed $scopeIn
     *
     * @return any $ScopeOut
     */
    public function runCommand(array $scopectl, $scopeIn = array())
    {
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
            $this->warning('runCommand/scopeIn is not an array!!!');
        }

        return mcpMenuClass::runCommand($scopectl, $scopeIn);
    }

    /**
     * runSequence inside actions.
     *
     * @param mixed $actions
     * @param mixed $scopeIn
     *
     * @return any $ScopeOut
     */
    public function runSequence(array $actionseq, $scopeIn = array())
    {
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
            $this->warning('runSequence/scopeIn is not an array!!!');
        }

        return mcpMenuClass::runSequence($actionseq, $scopeIn);
    }

    /**
     * Run Module as Menu sequence.
     *
     * @param string $action  name of the Doctrine
     * @param array  $scopeIn Input Array with the value need to work
     *
     * @return any $ScopeOut
     */
    public function runMenu($action, $scopeIn = array())
    {
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
            $this->warning('runMenu/scopeIn is not an array!!!');
        }

        return mcpMenuClass::runMenu($action, $scopeIn);
    }

    /**
     * Run Module as Tags sequence.
     *
     * @param string $action  name of the Doctrine
     * @param array  $scopeIn Input Array with the value need to work
     *
     * @return any $ScopeOut
     */
    public function runTag($action, $scopeIn = array(), $buffer = false)
    {
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
            $this->warning('runTag/scopeIn is not an array!!!');
        }
        if ($action == null) {
            return null;
        }

        return mcpMenuClass::runTag($action, $scopeIn, $buffer);
    }

    /**
     * Run Module as Tags sequence.
     *
     * @param string $action  name of the Doctrine
     * @param array  $scopeIn Input Array with the value need to work
     *
     * @return any $ScopeOut
     */
    public function converTag($text, $scopeIn = array(), $label = null)
    {
        if (!is_array($scopeIn)) {
            $scopeIn = array('In' => $scopeIn);
            $this->warning('converTag/scopeIn is not an array!!!');
        }
        if ($label == null) {
            $label = $this->defapp;
        }

        return mcpMenuClass::TagConverter($text, $scopeIn, $label);
    }

    /////////////////////////////////////////////////////////////////////////////
    // EVENT CONTROLLER
    /////////////////////////////////////////////////////////////////////////////

    /**
     * @param null $resname
     */
    public function RunEvent($resname = null)
    {
        if ($resname == null) {
            return false;
        }
        if (isset($this->event[$resname])) {
            $this->common[$resname] = $this->runSequence($this->event[$resname], $this->common);
        }

        return null;
    }

    /**
     * @param $resname name of value
     * @param $revalue values
     *
     * @return bool if operation coplete success true (othervise false)
     */
    public function addEvent($resname, $subcriber, $action = array())
    {
        if (!is_array($action)) {
            $action = array();
        }
        if ($resname == null || $subcriber == null) {
            return false;
        }
        if (!isset($this->event[$resname])) {
            $this->event[$resname] = array();
        }
        if ($action == null) {
            if (isset($this->event[$resname][$subcriber])) {
                unset($this->event[$resname][$subcriber]);
            }
        } else {
            $this->event[$resname][$subcriber] = $action;
        }

        return true;
    }

    /////////////////////////////////////////////////////////////////////////////
    // UCM CONTROLLER
    /////////////////////////////////////////////////////////////////////////////

    /**
     * UCM function.
     *
     * @param array $scopein
     *
     * @return UniversalContentManager
     */
    public function ucm($scopein = null)
    {
        return new \LinHUniX\Mcp\Tools\UniversalContentManager($scopein);
    }
}
