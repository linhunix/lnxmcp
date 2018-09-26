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
include __DIR__ . "/App/Head.php";
/**
 * Add a directory in phar removing whitespaces from PHP source code
 * 
 * @param Phar $phar
 * @param string $sDir 
 */
function addDir($phar, $sDir, $baseDir = null) {
    $oDir = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($sDir), RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($oDir as $sFile) {
        if (preg_match('/\\.php$/i', $sFile)) {
            addFile($phar, $sFile, $baseDir);
        }
    }
}
/**
 * Add a file in phar removing whitespaces from the file
 * 
 * @param Phar $phar
 * @param string $sFile 
 */
function addFile($phar, $sFile, $baseDir = null) {
    if (null !== $baseDir) {
        $phar->addFromString(substr($sFile, strlen($baseDir) + 1), php_strip_whitespace($sFile));
    } else {
        $phar->addFromString($sFile, php_strip_whitespace($sFile));
    }
}
$cfg["lnxmcp"]->rem("Phar Ize tool ");
$srcRoot = $app_path;
$buildRoot = $srcRoot . "/dist";
$filename = 'lnxmcp.phar';
$pharPath = $buildRoot . "/$filename";
$cfg["lnxmcp"]->debugVar("Pharize", "srcRoot", $srcRoot);
$cfg["lnxmcp"]->debugVar("Pharize", "buildRoot", $buildRoot);
$cfg["lnxmcp"]->debugVar("Pharize", "filename", $filename);
$cfg["lnxmcp"]->debugVar("Pharize", "pharPath", $pharPath);
try {
    if (!file_exists("$srcRoot/vendor")) {
        $cfg["lnxmcp"]->waring("Error: to compile the PHAR file you need to execute composer install inside the ZFTool module\n");
        exit();
    }
    if (file_exists($pharPath)) {
        $cfg["lnxmcp"]->info("removing old phar file");
        unlink($pharPath);
    }
    if (!is_dir($buildRoot)) {
        mkdir($buildRoot);
    }
    $phar = new \Phar($pharPath, 0, $filename);
    $phar->startBuffering();
    addDir($phar, "$srcRoot/App", $srcRoot);
    addDir($phar, "$srcRoot/Tool", $srcRoot);
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
\$ftmcp_path="phar://$filename/";
\$lnxmcp_vers=array(
 "ver"=>$version,
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
require 'phar://$filename/App/Head.php';
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
    $cfg["lnxmcp"]->critical("phpize error", $e->getMessage());
}