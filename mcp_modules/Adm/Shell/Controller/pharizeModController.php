<?php
namespace LinHUniX\LnxMcpAdmShell\Controller;
use LinHUniX\Mcp\Model\mcpBaseModelClass;
/**
 * LinHUniX Web Application Framework.
 *
 * @author Andrea Morello <lnxmcp@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 *
 * @version GIT:2018-v2
 */
class pharizeModController extends mcpBaseModelClass {
 
    /**
     * Add a directory in phar removing whitespaces from PHP source code
     * 
     * @param Phar $phar
     * @param string $sDir 
     */
    private function addDir($phar, $sDir, $baseDir = null)
    {
        $oDir = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sDir),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($oDir as $sFile) {
            if (basename($sFile) != ".." && basename($sFile) != ".") {
                if (is_dir($sFile)) {
                    lnxmcp()->info("add dir $sFile");
                    $this->addDir($phar, $sFile, $baseDir);
                } else {
                    lnxmcp()->info("add file $sFile");
                    $this->addFile($phar, $sFile, $baseDir);
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
    private function addFile($phar, $sFile, $baseDir = null)
    {
        if (null !== $baseDir) {
            $phar->addFromString(substr($sFile, strlen($baseDir) + 1), php_strip_whitespace($sFile));
        } else {
            $phar->addFromString($sFile, php_strip_whitespace($sFile));
        }
    }
    /**
     * 
     */
    private function getStub(){
        $stub = <<<EOF
<?php
/**
 * LinHUniX Web Application Framework
 * @author Andrea Morello <lnxmcp@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   GPL v3 See LICENSE.md
 * @link https://github.com/linhunix/lnxmcp/wiki
 * @category LN4 Project.
 * @version GIT:2018-v3 
 */
global \$mcp_path,\$app_path,\$lnxmcp_phar,\$lnxmcp_purl;
Phar::mapPhar(basename(__FILE__));
if (!isset(\$app_path)) {
    \$app_path = \$_SERVER['DOCUMENT_ROOT'];
    if (empty(\$app_path)) {
        \$app_path = realpath(__DIR__ . "/../");
        \$_SERVER['DOCUMENT_ROOT'] = \$app_path;
    } else {
        \$app_path = realpath(\$app_path) . "/";
    }
}
/*LNXMCP-INIT*/ 
if (function_exists("lnxmcp")==false){
     include \$app_path."/app.php" ; 
}; 
lnxmcp()->addModule('phar://'.__FILE__);
lnxmcp()->imhere(); 
/*LNXMCP-END*/
__HALT_COMPILER();
EOF;
        return $stub;        
    }
    
    /**
     *  Ideally this method shuld be used to insert the model code and the other are to be used only as normal.
     */
    protected function moduleCore() {
        global $filename, $version;
        ini_set('phar.readonly', false);
        $app_path=$this->argIn['app.path'];
        $php_bcmd=$this->argIn['cmd.php'];
        lnxmcp()->rem("Phar Ize tool ");
        $srcRoot = realpath($app_path);
        $buildRoot = $srcRoot . DIRECTORY_SEPARATOR . "dist";
        $srcRoot .= DIRECTORY_SEPARATOR.'src';
        $filename = 'tmp.lnxmcp.module.phar';
        $filedest = 'lnxmcp.module.phar';
        $pharPath = $buildRoot . DIRECTORY_SEPARATOR . $filename;
        lnxmcp()->debugVar("Pharize", "srcRoot", $srcRoot);
        lnxmcp()->debugVar("Pharize", "buildRoot", $buildRoot);
        lnxmcp()->debugVar("Pharize", "filename", $filename);
        lnxmcp()->debugVar("Pharize", "pharPath", $pharPath);
        lnxmcp()->debug("try to load:".$srcRoot.DIRECTORY_SEPARATOR.'mcp.module.json');
        if (file_exists($srcRoot.DIRECTORY_SEPARATOR.'mcp.module.json')){
            lnxmcp()->debug("Load json:".$srcRoot.DIRECTORY_SEPARATOR.'mcp.module.json');
            $modcfg=lnxGetJsonFile($srcRoot.DIRECTORY_SEPARATOR.'mcp.module.json');
        }
        if (!is_array($modcfg)){
            $modcfg=array();
        }
        if(!isset($modcfg['vendor'])){
            $modcfg['vendor']=lnxmcp()->getResource('def');
        }
        if(!isset($modcfg['module'])){
            $modcfg['module']='core';
        }
        if(!isset($modcfg['version'])){
            $modcfg['version']='1.0.0';
        }
        $filedest=$modcfg['vendor'].'.'.$modcfg['module'].'.phar';
        lnxmcp()->debugVar("Pharize", "filedest", $filedest);
        $destPath = $buildRoot . DIRECTORY_SEPARATOR . $filedest;
        lnxmcp()->debugVar("Pharize", "destPath", $destPath);


        try {
            if (file_exists($destPath)) {
                lnxmcp()->info("removing old phar file");
                unlink($destPath);
            }
            if (!is_dir($buildRoot)) {
                mkdir($buildRoot);
                lnxmcp()->info("making phar dist dir");
            }
            lnxmcp()->info("making phar on " . $destPath);
            $phar = new \Phar($destPath, \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::KEY_AS_FILENAME, $filedest);
            lnxmcp()->info("init phar ");
            $phar->startBuffering();
            lnxmcp()->info("phar: add mcp modules source ");
            $this->addDir($phar, $srcRoot, $srcRoot);
            lnxmcp()->info("phar: add init ");
            $stub=$this->getStub();
            $phar->setStub($stub);
            $phar->stopBuffering();
            if (file_exists($destPath)) {
                echo "Phar created successfully in $destPath\n";
                //rename($pharPath,$destPath);
                chmod($destPath, 0755);
            } else {
                echo "Error during the compile of the Phar file $destPath\n";
                exit(2);
            }

        } catch (\Exception $e) {
            lnxmcp()->warning("phpize error!! :" . $e->getMessage());
            lnxmcp()->critical("if is disable use : php -d phar.readonly=0 mcp_extras/pharize.php ");
            echo $e->getTraceAsString();
        }
    }
}