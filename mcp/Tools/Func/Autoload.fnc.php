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
