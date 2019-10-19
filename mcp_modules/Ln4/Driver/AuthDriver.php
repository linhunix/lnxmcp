<?php
namespace LinHUniX\Ln4\Driver;
use LinHUniX\Mcp\Model\mcpServiceModelClass;

/**
 * LinHUniX Web Application Framework
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 */
class AuthDriver extends mcpServiceModelClass {
    protected $session_id;
    protected $session_privacy;
    protected $session_user;
    protected $session_groups;
    protected $session_data;
    protected $session_authlevel;

    protected function moduleInit(){
        $this->spacename=__NAMESPACE__;
        $this->classname=__CLASS__;
    }
    /**
     * Check if session is ready or make logout
     */
    public function user_session(){
        $session_id=$this->getMcp()->getCommon("session.id");
        return lnxCacheCtl(
            "user_session_".$session_id,
            3600,
            array(
                "load"=>array(
                    "type"=>"service",
                    "module"=>"Auth",
                    "name"=>"auth",
                    "input"=>array(
                        "T"=>"user",
                        "E"=>"logout"
                    )
                )
            ),
            $this->argIn
        );
    }
    

}