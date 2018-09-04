<?php
/**
 * LinHUniX Web Application Framework
 *
 * @author Andrea Morello <andrea.morello@freetimers.com>
 * @copyright LinHUniX Communications Ltd, 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v3
 */

namespace LinHUniX\Mcp;

use LinHUniX\Mcp\Provider\settingsProvider;
use LinHUniX\Mcp\Model\mcpServiceProviderClass;
use LinHUniX\Mcp\Model\mcpBaseModelClass;
use LinHUniX\Mcp\Model\mcpConfigArrayModelClass;

/*
 * this Master Control Programs Class is to prepare 
 * to use a slim or simphony controller 
 * as a difference that the Ashley LinHUniX MVC class is to easy implemts 
 * because use only a easy class mcpBaseModelClass and two methods run and check 
 * and support the $GLOBAL vars like "cfg"
 * this class are exportable on the future as abstact controller 
 * 
 * @see [vendor]/src/Head.php  
 */

final class masterControlProgram
{
    /**
     *
     * @var string path of the Applications locations 
     */
    private $pathapp;
    /**
     *
     * @var string path of the template positions on the code
     */
    private $pathtpl;
    /**
     *
     * @var Slim Content as a test of integrations 
     */
    private $cfg; // is a test class to integrate slim on the code 
    /**
     * Short Name of this application
     * @var string 
     */
    private $defapp;
    /**
     * Short Name of the vendors 
     * @var string 
     */
    private $defvnd = "LinHUniX";
    /**
     *
     * @var class 
     */
    private $mcpCore;
    /**
     *
     * @var class 
     */
    private $mcpLogging;
    /**
     *
     * @var class 
     */
    private $mcpTools;
    /**
     * Create a slim app integration, add container and set the log as 
     * @param Container $cfg was load as master controller (lnxmcp)
     */
    public function __construct(array $scopeIn)
    {
        $this->pathapp = $scopeIn["app.path"] . "/App/";
        $this->defapp = ucwords($scopeIn["app.def"]);
        $this->pathtpl = $this->pathapp . "Template/";
        $this->pathsrc = $this->pathapp . "Module/";
        $this->cfg = new mcpConfigArrayModelClass();
        $this->cfg['php'] = PHP_VERSION;
        $this->cfg["app.debug"] = false;
        $this->cfg["app.level"] = "WARNING";
        foreach ($scopeIn as $sname => $value)
        {
            $this->cfg[$sname] = $value;
        }
        $this->cfg["app.timezone"] = "Europe/London";
        // LOGGING PROVIDER
        // intrigante devo ragionare su come gestire l'evento 
        $this->register(new settingsProvider());
        $this->mcpCore = new Component\mcpCoreClass($this);
        $this->mcpLogging = new Component\mcpDebugClass($this);
        $this->mcpTools = new Component\mcpToolsClass();
        // LEGACY SETTINGS
        $this->legacySetting();
    }
    /**
     * generate the global vars like older system
     * update the data of the Input Array
     */
    public function legacySetting()
    {
        $this->info("Start Legacy Env");
        $this->cfg["lnxmcp"] = $this;
        $GLOBALS["cfg"] = &$this->cfg;
    }

    /**
     * @return string
     */
    public function register (mcpServiceProviderClass $service)
    {
        $service->register($this,$this->cfg);
    }
    /**
     * @param null $resname
     * @return null object
     */
    public function getCfg($resname=null){
        if ($resname==null){
            return $this->cfg;
        }
        if (isset($this->cfg[$resname])){
            return $this->cfg[$resname];
        }
        return null;
    }

    /**
     * @param $resname name of value
     * @param $revalue values
     * @return bool if operation coplete success true (othervise false)
     */
    public function setCfg($resname,$revalue){
        if (($resname=="")||($resname==null)){
            return false;
        }
        if (($revalue=="")||($revalue==null)){
            return false;
        }
        if ($revalue=="."){
            if (isset($this->cfg[$resname])) {
                unset($this->cfg[$resname]);
            }
        }else{
            $this->cfg[$resname]=$revalue;
        }
        return true;
    }
    /**
     * load a specific app resource 
     * @param type $resource name ( - "app.")
     * @return any content of specific resource 
     */
    public function getResource($resource)
    {
        $this->info("CALL DIRECT RESOURCE app." . $resource);
        if (isset($this->cfg["app." . $resource]))
        {
            return $this->cfg["app." . $resource];
        }
        return null;
    }
    /**
     * update the data of the Input Array
     * @param string $name
     * @param any $value
     */
    public function setScopeIn($name, $value)
    {
        $this->mcpCore->setScopeIn($name, $value);
    }
    /**
     * update the data of the output Array
     * @param string $name
     * @param any $value
     */
    public function setScopeOut($name, $value)
    {
        $this->mcpCore->setScopeOut($name, $value);
    }
    /**
     * update the data of the Control Array
     * @param string $name
     * @param any $value
     */
    public function setScopeCtl($name, $value)
    {
        $this->mcpCore->setScopeCtl($name, $value);
    }
    /**
     * return the input array
     * @return array ()
     */
    public function getScopeIn()
    {
        return $this->mcpCore->getScopeIn();
    }
    /**
     * return the array array
     * @return array ()
     */
    public function getScopeOut()
    {
        return $this->mcpCore->getScopeOut();
    }
    /**
     * return the array of result (empty array if is null)
     * @return array ()
     */
    public function getScopeOutResult()
    {
        $res = $this->getScopeOut();
        if (isset($res["return"]))
        {
            return $res["return"];
        }
        return array();
    }
    /**
     * is is valid and true returun the value of the status, for all other case is false 
     * @return bool status 
     */
    public function getScopeOutStats()
    {
        $res = $this->getScopeOut();
        if (isset($res["status"]))
        {
            if ($res["status"] == true)
            {
                return true;
            }
        }
        return false;
    }
    /**
     * rest output scope varable with out clean historiy and status
     */
    public function rstScopeOut()
    {
        $this->mcpCore->rstScopeOut();
    }
    /**
     * return the Control array
     * @return array ()
     */
    public function getScopeCtl()
    {
        return $this->mcpCore->getScopeCtl();
    }
    /**
     * Set status of elaborations 
     * @param bool $status
     * @param string $message
     */
    public function setStatus($status, $message)
    {
        $this->mcpCore->setStatus($status, $message);
    }
    /**
     * Set Te actuos IPL area is working 
     * @param type $area
     */
    public function setIpl($area)
    {
        $this->mcpCore->setWorkingArea($area);
    }
    /**
     * 
     * @param string $path
     * @param string $callname
     * @param bool $ispreload
     * @param array $scopeIn
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
     * Load a module or a template and clear the vars 
     */
    public function loadModule()
    {
        $this->mcpCore->moduleLoader();
    }
    /**
     *  load and execute module and clear the vars after results 
     *  @return array results
     */
    public function callModule()
    {
        return $this->mcpCore->moduleCaller();
    }
    /**
     * debug class (level debug)
     * @param string $message
     */
    public function debug($message)
    {
        if ($this->cfg["app.debug"] == true)
        {
            $this->mcpLogging->debug($message);
        }
    }
    /**
     * debug class (level debug)
     * @param string $message
     * @param type $name value name;
     * @param type $value value content
     */
    public function debugVar($message, $name, $value)
    {
        $this->debug($message . ":" . $name . "=" . print_r($value, 1));
    }
    /**
     * debug class (level notice/info)
     * @param string $message
     */
    public function info($message)
    {
        if ($this->cfg["app.debug"] == true)
        {
            $this->mcpLogging->info($message);
        }
    }
    /**
     * debug class (level notice/info)
     * @param string $message
     */
    public function imhere()
    {
        if ($this->cfg["app.debug"] == true)
        {
            $this->mcpLogging->imhere();
        }
    }
    /**
     * debug class (level warning)
     * @param string $message
     */
    public function warning($message)
    {
        $this->mcpLogging->warning($message);
    }
    /**
     * debug class (level error)
     * @param string $message
     */
    public function error($message)
    {
        $this->mcpLogging->error($message);
    }
    /**
     * debug class (level critical and die)
     * @param string $message
     */
    public function critical($message)
    {
        $this->mcpLogging->critical($message);
    }
    /**
     * not found page
     * @param string $message
     */
    public function notFound($message)
    {
        $this->mcpLogging->notFound($message);
    }
    /**
     * Make a Web Rem  with this message
     * @param string $message
     */
    public function Rem($message)
    {
        $this->mcpLogging->webRem($message);
    }
    /**
     * Make a Web dumo with html tag of with this message and var
     * @param string $message
     */
    public function display($message, $var)
    {
        $this->mcpLogging->webDump($message, $var);
    }
    /**
     * move to php file
     * @param string $newphpfile
     */
    public function move($newphpfile)
    {
        $this->mcpLogging->move($newphpfile);
    }
    /**
     * header redirect and more
     * @param string $string rules
     * @param bool $end die after change
     * @param bool $replace remplace header 
     * @param int $retcode html return code if need 
     */
    public function header($string, $end = false, $replace = true, $retcode = null)
    {
        $this->mcpLogging->header($string, $end, $replace, $retcode);
    }
    /**
     * Clear String from Escape chars (mcpTools)
     * @param string $string
     * @return string
     */
    public function escapeClear($string)
    {
        return $this->mcpTools->escapeClear($string);
    }
    /**
     * convert String to a standard ascii (mcpTools)
     * @param string $string
     * @return string
     */
    public function ConvertToAscii($string)
    {
        return $this->mcpTools->toAscii($string);
    }
    /**
     * Request save to session 
     * @param type $arguments name of the request 
     * @param type $onlyPost if true don-t read get 
     */
    public function Req2Session($arguments, $onlyPost = false)
    {
        return $this->mcpTools->Req2Session($arguments, $onlyPost);
    }
    /**
     * Install a provider inside Slim>> Pimple Container
     * @param ServiceProviderInterface $provider Standard Pimple Provider
     */
    public function RegiterPrivider(ServiceProviderInterface $provider)
    {
        $this->cfg->register($provider);
    }
    /**
     * 
     * @param string $callname name of the functionality
     * @param string $path path where present the basedirectory of the data
     * @param bool $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $modinit Module name where is present the code and be load and initalized
     * @param string $subcall used if the name of the functionality ($callname) and the subcall are different 
     * @param string $vendor this code is part of specific vendor (ex ft ) 
     * @param string $type is a Page, Block, Controller, Driver 
     * @return array $ScopeOut 
     */
    public function module($callname, $path = null, $ispreload = false, array $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null, $type = null)
    {
        if ($path == null)
        {
            $path = $this->pathsrc;
        }
        $this->statmentModule($path, $callname, $ispreload, $scopeIn, $modinit, $subcall, $vendor, $type);
        return $this->callModule();
    }
    /**
     * 
     * @param string $callname name of the functionality
     * @param string $path path where present the basedirectory of the data
     * @param bool $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $modinit Module name where is present the code and be load and initalized
     * @param string $subcall used if the name of the functionality ($callname) and the subcall are different 
     * @param string $vendor this code is part of specific vendor (ex ft ) 
     * @param string $type is a Page, Block, Controller, Driver 
     * @return array $ScopeOut 
     */
    public function template($callname, $path = null, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null, $type = null)
    {
        if ($path == null)
        {
            $path = $this->pathtpl;
        }
        $this->statmentModule($path, $callname, $ispreload, $scopeIn, $modinit, $subcall, $vendor, $type);
        $this->loadModule();
    }
    /**
     *  similar with module but at end exit (0 okdone - 1 with error )
     * @param string $callname name of the functionality
     * @param string $path path where present the basedirectory of the data
     * @param bool $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $modinit Module name where is present the code and be load and initalized
     * @param string $subcall used if the name of the functionality ($callname) and the subcall are different 
     * @param string $vendor this code is part of specific vendor (ex ft ) 
     * @param string $type is a Page, Block, Controller, Driver 
     * @return array $ScopeOut 
     */
    public function moduleGoTo($callname, $path = null, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null, $type = null)
    {
        $res = 0;
        if (empty($callname))
        {
            $this->critical("Moving to Null Error");
        }
        $scopeOut = module($callname, $path = null, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null, $type = null);
        if (isset($scopeOut["status"]))
        {
            if ($scopeOut["status"] == false)
            {
                $res = 1;
            }
        }
        exit($res);
    }
    /**
     * similar to module but to easy 
     * @param string $libname  name of the functionality
     * @param array $scopeIn Input Array with the value need to work 
     * @return array $ScopeOut 
     */
    public function moduleRun($libname, $scopeIn = array())
    {
        return $this->module($libname, $this->pathsrc, false, $scopeIn);
    }
    /**
     * Run Module as Loader 
     * @param string $cfgvalue name of the Doctrine 
     * @param string $modinit Module name where is present the code and be load and initalized
     * @param string $path path where present the basedirectory of the data
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $subcall used if the name of the functionality ($callname) and the subcall are different 
     * @return array $ScopeOut 
     */
    public function loaderApp($subcall, $modinit, $scopeIn = array())
    {
        $this->info("MCP>>".$this->defapp.">>loader>>" . $subcall);
        return $this->module("tmp", $this->pathsrc, true, $scopeIn, $modinit, $subcall, $this->defapp, "Loader");
    }
    /**
     * Run Module as Loader 
     * @param string $cfgvalue name of the Doctrine 
     * @param string $modinit Module name where is present the code and be load and initalized
     * @param string $path path where present the basedirectory of the data
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $subcall used if the name of the functionality ($callname) and the subcall are different 
     * @return array $ScopeOut 
     */
    public function loaderCommon($subcall, $modinit, $scopeIn = array())
    {
        $this->info("MCP>>loader(C)>>" . $subcall);
        return $this->module("tmp", $this->pathsrc, true, $scopeIn, $modinit, $subcall, $this->defvnd, "Loader");
    }
    /**
     * Run Module as Doctrine 
     * @param string $cfgvalue name of the Doctrine 
     * @param string $modinit Module name where is present the code and be load and initalized
     * @param string $path path where present the basedirectory of the data
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $subcall used if the name of the functionality ($callname) and the subcall are different 
     * @return array $ScopeOut 
     */
    public function doctrine($cfgvalue, $modinit = null, $path = null, $scopeIn = array(), $subcall = null)
    {
        $this->info("MCP>>".$this->defapp."doctrine>>" . $cfgvalue);
        return $this->module($cfgvalue, $path, true, $scopeIn, $modinit, $subcall, $this->defapp, "Doctrine");
    }
    /**
     * Run Module as Driver 
     * @param string $libname name of the driver 
     * @param bool $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $modinit Module name where is present the code and be load and initalized
     * @param string $subcall used if the name of the functionality ($callname) and the subcall are different 
     * @return array $ScopeOut 
     */
    public function driver($libname, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null)
    {
        $this->info("MCP>>driver(C)>>" . $libname);
        return $this->module($libname, $this->pathsrc, $ispreload, $scopeIn, $modinit, $subcall, $this->defvnd, "Driver");
    }
    /**
     * Run Module as database query 
     * @param string $dbproc name of the driver 
     * @param bool $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $modinit Module name where is present the code and be load and initalized
     * @param string $subcall used if the name of the functionality ($callname) and the subcall are different 
     * @return array $ScopeOut 
     */
    public function query($dbproc, $ispreload = true, $scopeIn = array(), $modinit = null, $subcall = null)
    {
        $this->info("MCP>>".$this->defapp.">>query>>" . $dbproc);
        return $this->module($dbproc, $this->pathsrc, $ispreload, $scopeIn, $modinit, $subcall, $this->defapp, "Query");
    }
    /**
     * Run Module as database query 
     * @param string $dbproc name of the driver 
     * @param bool $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $modinit Module name where is present the code and be load and initalized
     * @param string $subcall used if the name of the functionality ($callname) and the subcall are different 
     * @return array $ScopeOut 
     */
    public function queryr($dbproc, $ispreload = true, $scopeIn = array(), $modinit = null, $subcall = null)
    {
        $this->info("MCP>>".$this->defapp.">>query[R]>>" . $dbproc);
        return $this->module($dbproc, $this->pathsrc, $ispreload, $scopeIn, $modinit, $subcall, $this->defapp, "Query")["return"];
    }
    /**
     * Run Module as controller 
     * @param string $ctrlproc name of the driver 
     * @param bool $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $modinit Module name where is present the code and be load and initalized
     * @param string $subcall used if the name of the functionality ($callname) and the subcall are different 
     * @return array $ScopeOut 
     */
    public function controller($ctrlproc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null)
    {
        $this->info("MCP>>".$this->defapp.">>controller>>" . $ctrlproc);
        return $this->module($ctrlproc, $this->pathsrc, $ispreload, $scopeIn, $modinit, $subcall, $this->defapp, "Controller");
    }
    /**
     * Run Module as controller as common for all
     * @param string $ctrlproc name of the driver 
     * @param bool $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $modinit Module name where is present the code and be load and initalized
     * @param string $subcall used if the name of the functionality ($callname) and the subcall are different 
     * @return array $ScopeOut 
     */
    public function controllerCommon($ctrlproc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null)
    {
        $this->info("MCP>>controller(C)>>" . $ctrlproc);
        return $this->module($ctrlproc, $this->pathsrc, $ispreload, $scopeIn, $modinit, $subcall, $this->defvnd, "Controller");
    }
    /**
     * Run Module as ToolApi Components  
     * @param string $srvprc name of the driver 
     * @param bool $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $modinit Module name where is present the code and be load and initalized
     * @param string $subcall used if the name of the functionality ($callname) and the subcall are different 
     * @return array $ScopeOut 
     */ public function api($srvprc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null)
    {
        $this->info("MCP>>".$this->defapp.">>api>>" . $srvprc);
        return $this->module($srvprc, $this->pathsrc, $ispreload, $scopeIn, $modinit, $subcall, $this->defapp, "Api");
    }
    /**
     * Run Module as ToolApi Components  
     * @param string $srvprc name of the driver 
     * @param bool $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $modinit Module name where is present the code and be load and initalized
     * @param string $subcall used if the name of the functionality ($callname) and the subcall are different 
     * @return array $ScopeOut 
     */ public function apiCommon($srvprc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null)
    {
        $this->info("MCP>>api(C)>>" . $srvprc);
        return $this->module($srvprc, $this->pathsrc, $ispreload, $scopeIn, $modinit, $subcall, $this->defvnd, "Api");
    }
    /**
     * Run Module as service 
     * @param string $srvprc name of the driver 
     * @param bool $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $modinit Module name where is present the code and be load and initalized
     * @param string $subcall used if the name of the functionality ($callname) and the subcall are different 
     * @return array $ScopeOut 
     */ public function service($srvprc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null)
    {
        $this->info("MCP>>service>>" . $srvprc);
        return $this->module($srvprc, $this->pathsrc, $ispreload, $scopeIn, $modinit, $subcall, $this->defapp, "Service");
    }
    /**
     * Run Module as service 
     * @param string $srvprc name of the driver 
     * @param bool $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $modinit Module name where is present the code and be load and initalized
     * @param string $subcall used if the name of the functionality ($callname) and the subcall are different 
     * @return array $ScopeOut 
     */ public function serviceCommon($srvprc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null)
    {
        $this->info("MCP>>service(C)>>" . $srvprc);
        return $this->module($srvprc, $this->pathsrc, $ispreload, $scopeIn, $modinit, $subcall, $this->defvnd, "Service");
    }
    /**
     * Load a page with your ScopeIn
     * @param string $page name of the Page
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $modinit Module name where is present the code and be load and initalized
     */
    public function page($page, $scopeIn = array(), $modinit = null)
    {
        $this->info("MCP>>page>>" . $page);
        $this->template($page, $this->pathtpl, true, $scopeIn, $modinit, null, $this->defapp, "Page");
    }
    /**
     * Load a Block with your ScopeIn
     * @param string $block name of the Block
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $modinit Module name where is present the code and be load and initalized
     */
    public function block($block, $scopeIn = array(), $modinit = null)
    {
        $this->info("MCP>>block>>" . $block);
        $this->template($block, $this->pathtpl, true, $scopeIn, $modinit, null, $this->defapp, "Block");
    }
    /**
     * Load a Block with your ScopeIn
     * @param string $block name of the Block
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $modinit Module name where is present the code and be load and initalized
     */
    public function blockCommon($block, $scopeIn = array(), $modinit = null)
    {
        $this->info("MCP>>block(C)>>" . $block);
        $this->template($block, $this->pathsrc, true, $scopeIn, $modinit, null, $this->defapp, "Block");
    }
    /**
     * Run Controller and then load a page with your ScopeIn
     * @param string $block name of the Block and the controller
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $modinit Module name where is present the code and be load and initalized
     * @param string $pageinit Module name if is different for the page 
     */
    public function showPage($block, $scopeIn = array(), $modinit = null, $pageinit = null)
    {
        $this->info("MCP>>showPage>>" . $block);
        $this->mcpCore->setClearFlagOff();
        $this->controller($block, false, $scopeIn, $modinit);
        $scopePageIn = $this->getScopeOutResult();
        $this->mcpCore->setClearFlagOn();
        if ($pageinit == null)
        {
            $pageinit = $modinit;
        }
        $this->page($block, $scopePageIn, $pageinit);
    }
    /**
     * Run Controller and then load a page with your ScopeIn
     * @param string $block name of the Block and the controller
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $modinit Module name where is present the code and be load and initalized
     * @param string $pageinit Module name if is different for the page 
     */
    public function showCommonPage($block, $scopeIn = array(), $modinit = null, $pageinit = null)
    {
        $this->info("MCP>>showPage>>" . $block);
        $this->mcpCore->setClearFlagOff();
        $this->controllerCommon($block, false, $scopeIn, $modinit);
        $scopePageIn = $this->getScopeOutResult();
        $this->mcpCore->setClearFlagOn();
        if ($pageinit == null)
        {
            $pageinit = $modinit;
        }
        $this->page($block, $scopePageIn, $pageinit);
    }
    /**
     * Run Controller and then load a block with your ScopeIn
     * @param string $block name of the Block and the controller
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $controllerModule Module name where is present the code and be load and initalized
     * @param string $blockModule Module name if is different for the page 
     */
    public function showBlock($block, $scopeIn = array(), $controllerModule = null, $blockModule = null)
    {
        $this->info("MCP>>showBlock>>" . $block);
        $this->mcpCore->setClearFlagOff();
        $CtrlOut = $this->controller($block, false, $scopeIn, $controllerModule);
        $scopeCtl = $this->getScopeCtl();
        $this->mcpCore->setClearFlagOn();
        if ($blockModule == null)
        {
            $blockModule = $controllerModule;
        }
        $sb = true;
        if (isset($scopeCtl["showBlock"]))
        {
            if ($scopeCtl["showBlock"] == false)
            {
                $sb = false;
            }
        }
        if (isset($scopeCtl["changeBlock"]))
        {
            $block = $scopeCtl["changeBlock"];
        }
        if ($sb == true)
        {
            return $this->block($block, $CtrlOut, $blockModule);
        }
    }
    /**
     * Run Controller and then load a block with your ScopeIn
     * @param string $block name of the Block and the controller
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $controllerModule Module name where is present the code and be load and initalized
     * @param string $blockModule Module name if is different for the page 
     */
    public function showCommonBlock($block, $scopeIn = array(), $controllerModule = null, $blockModule = null)
    {
        $this->info("MCP>>showBlock>>" . $block);
        $this->mcpCore->setClearFlagOff();
        $CtrlOut = $this->controllerCommon($block, false, $scopeIn, $controllerModule);
        $scopeCtl = $this->getScopeCtl();
        $this->mcpCore->setClearFlagOn();
        if ($blockModule == null)
        {
            $blockModule = $controllerModule;
        }
        $sb = true;
        if (isset($scopeCtl["showBlock"]))
        {
            if ($scopeCtl["showBlock"] == false)
            {
                $sb = false;
            }
        }
        if (isset($scopeCtl["changeBlock"]))
        {
            $block = $scopeCtl["changeBlock"];
        }
        if ($sb == true)
        {
            return $this->block($block, $CtrlOut, $blockModule);
        }
    }
    /**
     * Run Controller and then load a block with your ScopeIn
     * @param string $block name of the Block and the controller
     * @param array $scopeIn Input Array with the value need to work 
     * @param string $controllerModule Module name where is present the code and be load and initalized
     * @param string $blockModule Module name if is different for the page 
     */
    public function showFullCommonBlock($block, $scopeIn = array(), $controllerModule = null, $blockModule = null)
    {
        $this->info("MCP>>showBlock>>" . $block);
        $this->mcpCore->setClearFlagOff();
        $CtrlOut = $this->controllerCommon($block, false, $scopeIn, $controllerModule);
        $scopeCtl = $this->getScopeCtl();
        $this->mcpCore->setClearFlagOn();
        if ($blockModule == null)
        {
            $blockModule = $controllerModule;
        }
        $sb = true;
        if (isset($scopeCtl["showBlock"]))
        {
            if ($scopeCtl["showBlock"] == false)
            {
                $sb = false;
            }
        }
        if (isset($scopeCtl["changeBlock"]))
        {
            $block = $scopeCtl["changeBlock"];
        }
        if ($sb == true)
        {
            return $this->blockCommon($block, $CtrlOut, $blockModule);
        }
    }
    /**
     *  clean the cache if is active 
     */
    public function flushCache()
    {
        if (isset($GLOBALS["cfg"]["app.cache"]))
        {
            $GLOBALS["cfg"]["app.cache"]->flush();
        }
        if (isset($GLOBALS["cfg"]["app.pdo.cache"]))
        {
            $GLOBALS["cfg"]["app.pdo.cache"]->flush();
        }
        if (isset($_SESSION))
        {
            $_SESSION["pdo.cache"] = array();
        }
        if (isset($GLOBALS["pdo.cache"]))
        {
            $GLOBALS["pdo.cache"] = array();
        }
    }
    /**
     *  Call Slim that is loaded inside and Run it
     */
    public function runSlim()
    {
        $this->slim->run();
    }
}
