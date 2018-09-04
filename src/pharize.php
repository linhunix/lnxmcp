#!/usr/bin/env php
<?php
/**
 * Freetimers Web Application Framework
 *
 * @author Andrea Morello <andrea.morello@freetimers.com>
 * @copyright Freetimers Communications Ltd, 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 *
 */
class mcpPharIze
{
    /**
     * Add a directory in phar removing whitespaces from PHP source code
     *
     * @param Phar $phar
     * @param string $sDir
     */
    private static function addDir ($phar, $sDir, $baseDir = null)
    {
        $oDir = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($sDir), RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($oDir as $sFile) {
            if (preg_match ('/\\.php$/i', $sFile)) {
                addFile ($phar, $sFile, $baseDir);
            }
        }
    }

    /**
     * Add a file in phar removing whitespaces from the file
     *
     * @param Phar $phar
     * @param string $sFile
     */
    private static function addFile ($phar, $sFile, $baseDir = null)
    {
        if (null !== $baseDir) {
            $phar->addFromString (substr ($sFile, strlen ($baseDir) + 1), php_strip_whitespace ($sFile));
        } else {
            $phar->addFromString ($sFile, php_strip_whitespace ($sFile));
        }
    }

    public static function run ($srcRoot,$buildRoot="",$full=false)
    {
        lnxmcp ()->rem ("Phar Ize tool ");
        if ($buildRoot=="") {
            $buildRoot = $srcRoot . "/dist";
        }
        $filename = 'lnxmcp.phar';
        $pharPath = $buildRoot . "/$filename";
        $version = lnxmcp ()->getResource("ver");
        lnxmcp ()->debugVar ("Pharize", "srcRoot", $srcRoot);
        lnxmcp()->debugVar ("Pharize", "buildRoot", $buildRoot);
        lnxmcp()->debugVar ("Pharize", "filename", $filename);
        lnxmcp()->debugVar ("Pharize", "pharPath", $pharPath);
        lnxmcp()->debugVar ("Pharize", "version", $version);
        try {
            if ($full) {
                if (!file_exists ("$srcRoot/vendor")) {
                    lnxmcp ()->waring ("Error: to compile the PHAR file you need to execute composer install inside the ZFTool module\n");
                    exit();
                }
            }
            if (file_exists ($pharPath)) {
                lnxmcp()->info ("removing old phar file");
                unlink ($pharPath);
            }
            if (!is_dir ($buildRoot)) {
                mkdir ($buildRoot);
            }
            $phar = new \Phar($pharPath, 0, $filename);
            $phar->startBuffering ();
            addDir ($phar, "$srcRoot/src", $srcRoot);
            if ($full) {
                addDir ($phar, "$srcRoot/Tool", $srcRoot);
                if (!file_exists ("$srcRoot/vendor")) {
                    addDir ($phar, "$srcRoot/vendor", $srcRoot);
                }
            }
            $stub = <<<EOF
#!/usr/bin/env php
<?php
/**
 * LinHUniX Web Application Framework
 *
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX Ltd, 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 *
 */
Phar::mapPhar('$filename');
\$lnxmcp_path="phar://$filename/";
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
            $phar->setStub ($stub);
            $phar->stopBuffering ();
            if (file_exists ($pharPath)) {
                echo "Phar created successfully in $pharPath\n";
                chmod ($pharPath, 0755);
            } else {
                echo "Error during the compile of the Phar file $pharPath\n";
                exit(2);
            }
        } catch
        (\Exception $e) {
            lnxmcp()->critical ("phpize error", $e->getMessage ());
        }
    }
}