#!/usr/bin/env php
<?php

/**
 * LinHUniX Web Application Framework
 *
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 *
 */
$app_path = realpath(__DIR__ . "/../");
//print(ini_get('phar.readonly'));
include $app_path . "/mcp/Head.php";
error_log(E_ALL);
ini_set("display_error", true);
ini_set('phar.readonly', false);
/**
 * Add a directory in phar removing whitespaces from PHP source code
 * 
 * @param Phar $phar
 * @param string $sDir 
 */
function addDir($phar, $sDir, $baseDir = null)
{
    $oDir = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($sDir),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($oDir as $sFile) {
        if (basename($sFile) != ".." && basename($sFile) != ".") {
            if (is_dir($sFile)) {
                lnxmcp()->info("add dir $sFile");
                addDir($phar, $sFile, $baseDir);
            } else {
                lnxmcp()->info("add file $sFile");
                addFile($phar, $sFile, $baseDir);
            }
        }
    }
}
/**
 * Add a file in phar removing whitespaces from the file
 * 
 * @param Phar $phar
 * @param string $sFile 
 */
function addFile($phar, $sFile, $baseDir = null)
{
    if (null !== $baseDir) {
        $phar->addFromString(substr($sFile, strlen($baseDir) + 1), php_strip_whitespace($sFile));
    } else {
        $phar->addFromString($sFile, php_strip_whitespace($sFile));
    }
}
lnxmcp()->rem("Phar Ize tool ");
$srcRoot = realpath($app_path);
$buildRoot = $srcRoot . DIRECTORY_SEPARATOR . "dist";
$filename = 'lnxmcp.phar';
$pharPath = $buildRoot . DIRECTORY_SEPARATOR . $filename;
lnxmcp()->debugVar("Pharize", "srcRoot", $srcRoot);
lnxmcp()->debugVar("Pharize", "buildRoot", $buildRoot);
lnxmcp()->debugVar("Pharize", "filename", $filename);
lnxmcp()->debugVar("Pharize", "pharPath", $pharPath);
try {
    if (file_exists($pharPath)) {
        lnxmcp()->info("removing old phar file");
        unlink($pharPath);
    }
    if (!is_dir($buildRoot)) {
        mkdir($buildRoot);
        lnxmcp()->info("making phar dist dir");
    }
    lnxmcp()->info("making phar on " . $pharPath);
    $phar = new Phar($pharPath, FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME, $filename);
    lnxmcp()->info("init phar ");
    $phar->startBuffering();
    lnxmcp()->info("phar: add mcp ");
    addDir($phar, "$srcRoot/mcp", $srcRoot);
    lnxmcp()->info("phar: add mcp_modules ");
    addDir($phar, "$srcRoot/mcp_modules/Chk", $srcRoot);
    lnxmcp()->info("phar: add init ");
    global $filename, $version;
    $stub = <<<EOF
#!/usr/bin/env php
<?php
/**
 * LinHUniX Web Application Framework
 *
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 *
 */
Phar::mapPhar('$filename');
\$lnxmcp_path="phar://$filename/";
\$lnxmcp_vers=array(
 "phar"=>true
 );            
if (!isset(\$app_path)) {
    \$app_path = \$_SERVER['DOCUMENT_ROOT'];
    if (empty(\$app_path)) {
        \$app_path = realpath(__DIR__ . "/../");
        \$_SERVER['DOCUMENT_ROOT'] = \$app_path;
    } else {
        \$app_path = realpath(\$app_path) . "/";
    }
}
require 'phar://$filename/mcp/Head.php';
__HALT_COMPILER();
EOF;
    $phar->setStub($stub);
    $phar->stopBuffering();
    if (file_exists($pharPath)) {
        echo "Phar created successfully in $pharPath\n";
        chmod($pharPath, 0755);
    } else {
        echo "Error during the compile of the Phar file $pharPath\n";
        exit(2);
    }
} catch (\Exception $e) {
    echo $e->getTraceAsString();
    lnxmcp()->warning("phpize error!! :" . $e->getMessage());
    lnxmcp()->critical("if is disable use : php -d phar.readonly=0 mcp_extras/pharize.php ");
}