<?php
namespace LinHUniX\Auth\Driver;

use LinHUniX\Mcp\Model\mcpServiceModelClass;
/**
 * 
 */
class SqliteDriver extends mcpServiceModelClass {
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

    public function user_session(){
        $session_id=$this->getMcp()->getCommon("session.id");
        lnxCacheCtl(
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