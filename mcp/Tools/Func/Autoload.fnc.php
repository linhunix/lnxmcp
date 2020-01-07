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
 * LnxMcpUse is a exntension to integrate the autoloader functionality
 * @param string $className 
 */
function LnxMcpUse($className){
    lnxmcp()->use($className);
}

/**
 * Load on config the data of a specific module folder
 * @param string $path
 */
function mcpLoadModPath($srcPath,$pharmok=false,$initmok=false) {
    if (!is_dir($srcPath)) {
        lnxmcp()->warning($srcPath." is not a directory");
    }
    $scannedItems = scandir($srcPath);
    foreach ($scannedItems as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        $mpok=false;
        $modfolder=$srcPath.DIRECTORY_SEPARATOR.$item;
        if (is_dir($modfolder)) {
            $mpok=true;
        }
        if ($mpok==false and $pharmok==true){
            if (strtolower(substr($item,-5,5))=='.phar'){
                $modfolder='phar://'.$modfolder;
                $mpok=true;
            }
        }
        if ($mpok==true){
            lnxmcp()->addModule($modfolder,null,$initmok);
        }
    }
}
