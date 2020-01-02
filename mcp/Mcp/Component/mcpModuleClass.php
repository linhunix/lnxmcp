<?php
/**
 * LinHUniX Web Application Framework.
 *
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 *
 * @version GIT:2018-v2
 */

namespace LinHUniX\Mcp\Component;

/**
 * Description of mcpDebugClass.
 *
 * @author andrea
 */
class mcpModuleClass
{
    /**
     * This function add module and namespace on the list of loadable module
     * @param string $modpath;
     * @param array $modcfg;
     * @param boolean $modinit;
     */
    public static function addModule($modpath,$modcfg=null,$modinit=false){
        if (!is_array($modcfg)){
            lnxmcp()->debug("try to load:".$modpath.DIRECTORY_SEPARATOR.'mcp.module.json');
            if (file_exists($modpath.DIRECTORY_SEPARATOR.'mcp.module.json')){
                lnxmcp()->debug("Load json:".$modpath.DIRECTORY_SEPARATOR.'mcp.module.json');
                $modcfg=lnxGetJsonFile($modpath.DIRECTORY_SEPARATOR.'mcp.module.json');
            }
        }
        if (!is_array($modcfg)){
            $modcfg=array();
        }
        if(!isset($modcfg['vendor'])){
            $modcfg['vendor']=lnxmcp()->getResource('def');
        }
        if(!isset($modcfg['module'])){
            $modcfg['module']=basename($modpath);
        }
        if(!isset($modcfg['version'])){
            $modcfg['version']='1.0.0';
        }
        $modset='app.mod.path.'.$modcfg['vendor'].'.'.$modcfg['module'];
        lnxmcp()->setCfg($modset,$modpath.DIRECTORY_SEPARATOR);
        $modver='app.mod.version.'.$modcfg['vendor'].'.'.$modcfg['module'];
        lnxmcp()->setCfg($modver,$modcfg['version']);     
        if(isset($modcfg['config'])){
            if(is_array($modcfg['config'])){
                foreach ($modcfg['config'] as $ck=>$cv){
                    lnxmcp()->setCfg($ck,$cv);
                }
            }
        }        
        if(isset($modcfg['common'])){
            if(is_array($modcfg['common'])){
                foreach ($modcfg['common'] as $ck=>$cv){
                    lnxmcp()->setCommon($ck,$cv);
                }
            }
        }
        if ($modinit==true){
            if(isset($modcfg['command'])){
                lnxmcp()->runCommand($modcfg['command'],$modcfg);
            }    
        }  
    }
    /**
     * useClass function is an  implementatiation of autoload
     * @param string $classname;
     * @return boolean;
     */
    public static function useClass($classname){
        $extarr=array('.php','.inc.php','.class.php');
        $classpath=str_replace('\\',DIRECTORY_SEPARATOR,$classname);
        $modpath=lnxmcp()->getCfg('app.path.module');
        foreach ($extarr as $ext){
            if (file_exists($modpath.DIRECTORY_SEPARATOR.$classpath.$ext)){
                include_once ( $modpath.DIRECTORY_SEPARATOR.$classpath.$ext);
                lnxmcp()->debug("load std class:". $modpath.DIRECTORY_SEPARATOR.$classpath.$ext);
                return true;
            }
        }
        $modpath=lnxmcp()->getCfg('mcp.path.module');
        foreach ($extarr as $ext){
            if (file_exists($modpath.DIRECTORY_SEPARATOR.$classpath.$ext)){
                include_once ( $modpath.DIRECTORY_SEPARATOR.$classpath.$ext);
                lnxmcp()->debug("load std class:". $modpath.DIRECTORY_SEPARATOR.$classpath.$ext);
                return true;
            }
        }
        $clsarr=explode('\\',$classname);
        if (count($clsarr)==3){
            $vnd=$clsarr[0];
            $mod=$clsarr[1];
            $ttl=$clsarr[2];
            $modpath=lnxmcp()->getCfg('app.mod.path.'.$vnd.'.'.$mod);
            if ($modpath!=null){
                foreach ($extarr as $ext){
                    if (file_exists($modpath.DIRECTORY_SEPARATOR.$ttl.$ext)){
                        include_once ( $modpath.DIRECTORY_SEPARATOR.$ttl.$ext);
                        lnxmcp()->debug("load std class:". $modpath.DIRECTORY_SEPARATOR.$ttl.$ext);
                        return true;
                    }
                }        
            }
        }
        if (count($clsarr)==4){
            $vnd=$clsarr[0];
            $mod=$clsarr[1];
            $typ=$clsarr[2];
            $ttl=$clsarr[3];
            $modpath=lnxmcp()->getCfg('app.mod.path.'.$vnd.'.'.$mod);
            if ($modpath!=null){
                foreach ($extarr as $ext){
                    if (file_exists($modpath.DIRECTORY_SEPARATOR.$typ.DIRECTORY_SEPARATOR.$ttl.$ext)){
                        include_once ( $modpath.DIRECTORY_SEPARATOR.$typ.DIRECTORY_SEPARATOR.$ttl.$ext);
                        lnxmcp()->debug("load std class:". $modpath.DIRECTORY_SEPARATOR.$typ.DIRECTORY_SEPARATOR.$ttl.$ext);
                        return true;
                    }
                }        
            }
            $modpath=lnxmcp()->getCfg('app.path.module');
            lnxmcp()->module($ttl, $modpath, true, array(), $mod, null, $vnd, $typ);
            return true;
        }
        lnxmcp()->warning("Std class not found:". $classname);
        return false;
    }

}