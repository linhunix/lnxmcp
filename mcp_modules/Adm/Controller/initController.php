<?php
namespace LinHUniX\LnxMcpAdm\Controller;
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
class initController extends mcpBaseModelClass {
    /**
     * 
     */
    protected function checkctl($defmod,$defcmd){
        $cmdfile = __DIR__.'/../'.$defmod.'/Controller/'.$defcmd.'Controller.php';
        $this->getMcp()->debug($cmdfile);
        if (file_exists($cmdfile)) {
            echo '<!-- '.$defcmd."/".$cmdfile." !-->\n";
            return true;
        }
        return false;
    }

    /**
     *  Ideally this method shuld be used to insert the model code and the other are to be used only as normal.
     */
    protected function moduleCore()
    {
        //// DEFINITION OF BINARY CONFIG;
        $defcmd = $this->argIn['cmd'];
        $defmod = $this->argIn['mode'];
        $apppath = lnxmcp()->getCfg('app.path');
        $cfgpath = lnxmcp()->getCfg('app.path.config');
        $mcpath = lnxmcp()->getCfg('mcp.path');
        $admpath =lnxmcp()->getCfg('app.mod.path.LinHUniX.LnxMcpAdm');
        $admpathS =lnxmcp()->getCfg('app.mod.path.LinHUniX.LnxMcpAdmShell');
        $admpathH =lnxmcp()->getCfg('app.mod.path.LinHUniX.LnxMcpAdmHttpd');
        $bincmd = PHP_BINARY;
        $setpath = $cfgpath.'/mcp.settings.json';
        $idxpath = $apppath.'/index.php';
        $id2path = $apppath.'/app.php';
        $cfgok = 'KO';
        $appok = 'KO';
        $idxok = 'KO';
        $setok = 'KO';
        if (is_dir($apppath)) {
            $appok = 'OK';
        }
        if ($apppath == '//') {
            $appok = 'KO';
        }
        if (is_dir($cfgpath)) {
            $cfgok = 'OK';
        }
        if (file_exists($setpath)) {
            $setok = 'OK';
        }
        if (file_exists($idxpath)) {
            $idxok = 'OK';
        } elseif (file_exists($id2path)) {
            $idxok = 'OK';
            $idxpath = $id2path;
        }
        $scopeCmdIn=array(
            'cmd'=>$defcmd,
            'adm.cmd'=>lnxmcp()->getCommon('web.adm.cmd'),
            'mode'=>$defmod,
            'cmd.php'=>$bincmd,
            'mcp.path'=>$mcpath,
            'adm.path'=>$admpath,
            'adm.path.shell'=>$admpathS,
            'adm.path.httpd'=>$admpathH,
            'app.ok'=>$appok,
            'app.path'=>$apppath,
            'cfg.ok'=>$cfgok,
            'cfg.path'=>$cfgpath,
            'idx.ok'=>$idxok,
            'idx.path'=>$idxpath,
            'set.ok'=>$setok,
            'set.path'=>$setpath
        );
        if($this->checkctl($defmod,$defcmd)){
            $this->getMcp()->controller($defcmd,false,$scopeCmdIn,'LnxMcpAdm'.$defmod,null,'LinHUniX');
        } else {
            echo "<!-- DEFAULT !-->\n";
            $this->getMcp()->controller('default',false,$scopeCmdIn,'LnxMcpAdm'.$defmod,null,'LinHUniX');
        }

    }
}