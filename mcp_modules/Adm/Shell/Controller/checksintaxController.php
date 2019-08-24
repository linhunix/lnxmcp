<?php 
namespace LinHUniX\LnxMcpAdmShell\Controller;
use LinHUniX\Mcp\Model\mcpBaseModelClass;
/**
 * LinHUniX Web Application Framework.
 *
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 *
 * @version GIT:2018-v2
 */
class checksintaxController extends mcpBaseModelClass {
    public static  $OK=0;
    public static  $KO=0;
    public static  $bincmd;
    /**
     * chkSintax
     * @param string $Pathphp
     */
    public function chkSintax($Pathphp) {
        try {
            $objects = scandir($Pathphp); 
            foreach ($objects as $object) { 
                if ($object != "." && $object != "..") { 
                    if ( is_dir($Pathphp."/".$object)) {
                        self::chkSintax($Pathphp."/".$object);
                    } elseif (strstr($Pathphp."/".$object,'.php')) {
                        $cmd=self::$bincmd.' -l '.$Pathphp."/".$object;
                        $res=shell_exec($cmd);
                        if (strstr($res,'No syntax errors detected')){
                            echo "OK for ".$Pathphp."/".$object.PHP_EOL;
                            self::$OK++;
                        }else{
                            self::$KO++;
                            echo "KO for ".$Pathphp."/".$object.PHP_EOL;
                            print_r($res);
                            echo PHP_EOL;
                        }
                    }
                }
            } 
        } catch (Exception $e)
        {
            $this->getMcp()->warning("Can execute check ".$e->getMessage());
        }
    }

    /**
     *  Ideally this method shuld be used to insert the model code and the other are to be used only as normal.
     */
    protected function moduleCore()
    {
        $pathapp=$this->argIn['app.path'];
        $phpcmd=$this->argIn['cmd.php'];
        echo "Run Check Syntax on ${pathapp}".PHP_EOL;
        self::$bincmd=$phpcmd;
        self::chkSintax($pathapp);
        echo "Total OK (".self::$OK.")".PHP_EOL;
        echo "Total KO (".self::$KO.")".PHP_EOL;
        if (self::$KO == 0) {
            echo "CHECK IS GOOD !!!".PHP_EOL;
            return true;
        } else {
            echo  "CHECK IS BAD !!!".PHP_EOL;
            return false;           
        }

    }   
}
