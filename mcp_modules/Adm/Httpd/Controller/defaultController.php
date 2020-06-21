<?php
namespace LinHUniX\LnxMcpAdmHttpd\Controller;
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

        $this->callCmd(
            array(
                "type"=> "serviceCommon",
                "name"=>"gfx",
                "module"=> "Gfx",
                "isPreload"=> false,
                "ScopeInRewrite"=> array(
                    "source"=> "Bs386",
                    "T"=> "DEF"
                )
            ),
            $_REQUEST
        );
        if (lnxmcp()->getCommon('user')=='guest'){
            $this->callCmd(
                array(
                    "type"=> "serviceCommon",
                    "name"=>"gfx",
                    "module"=> "Gfx",
                    "isPreload"=> false,
                    "ScopeInRewrite"=> array(
                        "source"=> "Gfx/Bs386/tpl/admlogin",
                        "mimetype"=> "text/html",
                        "T"=> "DYN"
                    )
                ),
                $_REQUEST
            );
            LnxMcpExit();
        }else{
            $this->callCmd(
                array(
                    "type"=> "serviceCommon",
                    "name"=>"gfx",
                    "module"=> "Gfx",
                    "isPreload"=> false,
                    "ScopeInRewrite"=> array(
                        "source"=> "Gfx/Bs386/tpl/lnxmcpadm",
                        "mimetype"=> "text/html",
                        "T"=> "DYN"
                    )
                ),
                $_REQUEST
            );
            LnxMcpExit();
        }
    }
}