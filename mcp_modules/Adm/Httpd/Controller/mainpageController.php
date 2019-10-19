<?php
namespace LinHUniX\LnxMcpAdmHttpd\Controller;
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
class mainpageController extends mcpBaseModelClass {


    protected function moduledef() {
        echo "<h1>SUMMARY:".$this->argIn['adm.cmd']."</h1>";
        echo "<PRE>".PHP_EOL;
        echo '//////////////////////////////////////////////////////////'.PHP_EOL;
        echo '|-> PATH APP:'.$this->argIn['app.path'].'('.$this->argIn['app.ok'].')'.PHP_EOL;
        echo '|-> PATH IDX:'.$this->argIn['idx.path'].'('.$this->argIn['idx.ok'].')'.PHP_EOL;
        echo '|-> PATH CFG:'.$this->argIn['cfg.path'].'('.$this->argIn['cfg.ok'].')'.PHP_EOL;
        echo '|-> PATH STS:'.$this->argIn['set.path'].'('.$this->argIn['set.ok'].')'.PHP_EOL;
        echo '|-> PATH MCP:'.$this->argIn['mcp.path'].PHP_EOL;
        echo '|-> PATH ADM:'.$this->argIn['adm.path'].PHP_EOL;
        echo '|-> PATH ADS:'.$this->argIn['adm.path.shell'].PHP_EOL;
        echo '|-> PATH ADH:'.$this->argIn['adm.path.httpd'].PHP_EOL;
        echo '|-> PATH PHP:'.$this->argIn['cmd.php'].PHP_EOL;
        echo '|-> VERS PHP:'.PHP_VERSION.PHP_EOL;
        echo '|-> VERS SYS:'.PHP_OS.PHP_EOL;
        echo '|-> NAME SYS:'.$_SERVER['HOSTNAME'].PHP_EOL;
        echo '//////////////////////////////////////////////////////////'.PHP_EOL;
        echo "</PRE>".PHP_EOL;
    }

    /**
     * 
     *  Ideally this method shuld be used to insert the model code and the other are to be used only as normal.
     */
        protected function moduleCore()
        {
            //  lnxmcp()->Rem($this->argIn);
            if (! isset($this->argIn['adm.cmd'])) {
                $this->moduledef();
            }
            switch ($this->argIn['adm.cmd']) {
                case 'Nsql':
                case 'nsql':
                    $this->callCmd(
                        array(
                            "type"=> "serviceCommon",
                            "name"=>"gfx",
                            "module"=> "Gfx",
                            "isPreload"=> false,
                            "ScopeInRewrite"=> array(
                                "source"=> '/../mcp_modules/Nsql/ViewAdm/main',
                                "mimetype"=> "text/html",
                                "tag"=>true,
                                "T"=> "DYN"
                            )
                        ),
                        $_REQUEST
                    );
                    break;;
                case 'Csv':
                case 'csv':
                    $this->callCmd(
                        array(
                            "type"=> "serviceCommon",
                            "name"=>"gfx",
                            "module"=> "Gfx",
                            "isPreload"=> false,
                            "ScopeInRewrite"=> array(
                                "source"=> '/../mcp_modules/Csv/ViewAdm/main',
                                "mimetype"=> "text/html",
                                "T"=> "DYN"
                            )
                        ),
                        $_REQUEST
                    );
                    break;;
                case 'Cmd':
                case 'cmd':
                    $this->callCmd(
                        array(
                            "type"=> "serviceCommon",
                            "name"=>"gfx",
                            "module"=> "Gfx",
                            "isPreload"=> false,
                            "ScopeInRewrite"=> array(
                                "source"=> "Gfx/Bs386/tpl/form",
                                "mimetype"=> "text/html",
                                "T"=> "DYN"
                            )
                        ),
                        $_REQUEST
                    );
                    break;;
                case 'Mail':
                case 'mail':
                    $this->callCmd(
                        array(
                            "type"=> "serviceCommon",
                            "name"=>"gfx",
                            "module"=> "Gfx",
                            "isPreload"=> false,
                            "ScopeInRewrite"=> array(
                                "source"=> "Gfx/Bs386/tpl/formMail",
                                "mimetype"=> "text/html",
                                "T"=> "DYN"
                            )
                        ),
                        $_REQUEST
                    );
                    break;;
                default:
                $this->moduledef();
                break;
            }
        }
    
}