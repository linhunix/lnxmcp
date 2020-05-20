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
class defaultController extends mcpBaseModelClass {
    /**
     *  Ideally this method shuld be used to insert the model code and the other are to be used only as normal.
     */
    protected function moduleCore()
    {

        echo '//////////////////////////////////////////////////////////'.PHP_EOL;
        echo '|-> PATH APP:'.$this->argIn['app.path'].'('.$this->argIn['appok'].')'.PHP_EOL;
        echo '|-> PATH IDX:'.$this->argIn['idx.path'].'('.$this->argIn['idxok'].')'.PHP_EOL;
        echo '|-> PATH CFG:'.$this->argIn['cfg.path'].'('.$this->argIn['cfgok'].')'.PHP_EOL;
        echo '|-> PATH STS:'.$this->argIn['set.path'].'('.$this->argIn['setok'].')'.PHP_EOL;
        echo '|-> PATH MCP:'.$this->argIn['mcp.path'].PHP_EOL;
        echo '|-> PATH ADM:'.$this->argIn['adm.path'].PHP_EOL;
        echo '|-> PATH ADS:'.$this->argIn['adm.path.shell'].PHP_EOL;
        echo '|-> PATH ADH:'.$this->argIn['adm.path.httpd'].PHP_EOL;
        echo '|-> PATH PHP:'.$this->argIn['cmd.php'].PHP_EOL;
        echo '|-> VERS PHP:'.PHP_VERSION.PHP_EOL;
        echo '|-> VERS SYS:'.PHP_OS.PHP_EOL;
        echo '|-> NAME SYS:'.$_SERVER['HOSTNAME'].PHP_EOL;
        echo '//////////////////////////////////////////////////////////'.PHP_EOL;
        echo '| Command List'.PHP_EOL;
        echo '//////////////////////////////////////////////////////////'.PHP_EOL;
        foreach (scandir(__DIR__) as $cfile) {
            if (strstr($cfile, 'cmd.txt') != false) {
                $ctag = explode('.', $cfile);
                echo '|-> '.$ctag[0];
                echo ' : '.file_get_contents(__DIR__.'/'.$cfile);
                echo PHP_EOL;
            }
        }
        echo '//////////////////////////////////////////////////////////'.PHP_EOL;
    }
}