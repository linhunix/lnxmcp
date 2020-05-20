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
class httpdController extends mcpBaseModelClass {
    public static  $OK=0;
    public static  $KO=0;
    public static  $bincmd;

    /**
     *  Ideally this method shuld be used to insert the model code and the other are to be used only as normal.
     */
    protected function moduleCore()
    {
        $apppath=$this->argIn['app.path'];
        $bincmd=$this->argIn['cmd.php'];
        $cmd=$bincmd. ' -S 127.0.0.1:9090 -t'.$apppath;
        echo "Run Httpd server [".$cmd."]".PHP_EOL;
        shell_exec($cmd);
    }   
}
