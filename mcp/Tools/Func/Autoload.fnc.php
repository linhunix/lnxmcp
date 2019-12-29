<?php

////////////////////////////////////////////////////////////////////////////////
// AUTOLOAD/CONFIG
////////////////////////////////////////////////////////////////////////////////
/**
 * A basic autoload implementation that should be compatible with PHP 5.2.
 *
 * @author pmg
 */
function legacyAutoload($className)
{
    global $autoLoadFolders;
    $className = str_replace('/LinHUniX/', '/', $className);
    foreach ($autoLoadFolders as $folder) {
        $classPath = $folder.DIRECTORY_SEPARATOR.$className.'.php';
        if (file_exists($classPath)) {
            LnxMcpCodeCompile($classPath);
            require_once $classPath;

            return true;
        }
    }

    return false;
}

/**
 * A basic autoload implementation that should be compatible with PHP 5.2.
 *
 * @author pmg
 */
function selfAutoLoad($srcPath)
{
    global $autoLoadFolders;
    $srcPath = realpath($srcPath);
    $scannedItems = scandir($srcPath);
    foreach ($scannedItems as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        if (is_dir($folder = $srcPath.DIRECTORY_SEPARATOR.$item)) {
            $autoLoadFolders[] = $folder;
        }
    }
    spl_autoload_register('legacyAutoload', true);
}


/**
 * Load on config the data of a specific module folder
 * @param string $path
 */
function mcpLoadModPath($path) {
    if (!is_dir($path)) {
        lnxmcp()->warning($path." is not a directory");
    }
    $scannedItems = scandir($srcPath);
    foreach ($scannedItems as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        $modfolder=$srcPath.DIRECTORY_SEPARATOR.$item;
        if (is_dir($modfolder)) {
            $modcfg=array();
            if (file_exists($modfolder.DIRECTORY_SEPARATOR.'mcp.modules.json')){
                $modcfg=lnxGetJsonFile($modfolder.DIRECTORY_SEPARATOR.'mcp.modules.json');
            }
            if(!isset($modcfg['vendor'])){
                $modcfg['vendor']=lnxmcp()->getResource('def');
            }
            if(!isset($modcfg['module'])){
                $modcfg['module']=$item;
            }
            $modset='app.mod.path.'.$modcfg['vendor'].'.'.$modcfg['module'];
            lnxmcp()->setCfg($modset,$modfolder.DIRECTORY_SEPARATOR);
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
