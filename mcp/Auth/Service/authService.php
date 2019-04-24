<?php

/**
 * LinHUniX Web Application Framework
 *
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 */

namespace LinHUniX\Auth\Service;

use LinHUniX\Mcp\Model\mcpServiceModelClass;

class authService extends mcpServiceModelClass {
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
     * standard 1 shot user
     */
    protected function moduleSingleTon() {
        $this->session_id=date("U");
        $this->session_user="guest";
        $this->session_group="guest";
        if (isset($_COOKIE["lnxmcp"])) {
            $this->$session_id=$_COOKIE["lnxmcp"];
        }
        $this->getMcp()->setCommon("session.id",$session_id);
        $cmd=$this->getSvcCfg("command.session");
        if (empty($cmd)) {
            $cmd=array(
                "type"=>"serviceCommon",
                "module"=>"Auth",
                "name"=>"sqlite",
                "input"=>array(
                    "T"=>"user",
                    "E"=>"session"
                )
            );
        }
        $this->session_data=$this->callCmd($cmd, $this->getSvcCfg());
        $this->session_update();
    }

    /**
     * session_update
     * [T]= session
     * [E]= update
     */
    public function session_update(){
        if (isset($this->session_data["privacy"])){
            $this->session_privacy=$this->session_data["privacy"];
        }
        if (isset($this->session_data["user"])){
            $this->session_user=$this->session_data["user"];
        }
        if (isset($this->session_data["groups"])){
            $this->session_groups=$this->session_data["groups"];
        }
        if (isset($this->session_data["authlevel"])){
            $this->session_authlevel=$this->session_data["authlevel"];
        }
        $this->getMcp()->setCommon("session.user",$this->session_user);
        $this->getMcp()->setCommon("session.groups",$this->session_groups);
        $this->getMcp()->setCommon("session.data",$this->session_data);
        $this->cookie_update();
    }
    /**
     * session_privacy
     * [T]= session
     * [E]= privacy
     */
    public function session_privacy(){
        if ($_REQUEST["Privacy"]="accepted"){
            $this->session_privacy=true;
            $this->session_data["privacy"]=true;
        }
        $this->cookie_update();
    }



    /**
     * cookie_update 
     * [T]= cookie
     * [E]= update
     */
    public function cookie_update(){
        if ($this->session_privacy == true) {
            $expire=$this->getSvcCfg("Session.expire");
            if (empty($expire)){
                $expire = time()+60*60;//1hours
            }
            setcookie("lnxmcp",$this->session_id,$expire);
        }
    }
    /**
     * cookie_delete 
     * [T]= cookie
     * [E]= delete
     */
    public function cookie_delete(){
        $expire = time()-3600;
        setcookie("lnxmcp",$this->session_id,$expire);
    }

    /**
     * user_login 
     * [T]= user
     * [E]= login
     */
    public function user_login() {
        $this->cookie_delete();
        if ($this->session_privacy != true) {
            return false;
        }
        $cmd=$this->getSvcCfg("command.login");
        if (empty($cmd)) {
            $cmd=array(
                "type"=>"serviceCommon",
                "module"=>"Auth",
                "name"=>"sqlite",
                "input"=>array(
                    "T"=>"user",
                    "E"=>"login"
                )
            );
        }
        $this->session_data=$this->callCmd($cmd, $this->getSvcCfg());
        $this->session_update();
    }
    /**
     * user_logout
     * [T]= user
     * [E]= logout
     */
    public function user_logout() {
        $this->cookie_delete();
        if ($this->session_privacy != true) {
            return false;
        }
        $cmd=$this->getSvcCfg("command.logout");
        if (! empty($cmd)) {
            $this->session_data=$this->callCmd($cmd, $this->getSvcCfg());
        }
    }
    /**
     * user_register 
     * [T]= user
     * [E]= register
     */
    public function user_register() {
        $this->cookie_delete();
        if ($this->session_privacy != true) {
            return false;
        }
        $cmd=$this->getSvcCfg("command.register");
        if (empty($cmd)) {
            $cmd=array(
                "type"=>"serviceCommon",
                "module"=>"Auth",
                "name"=>"sqlite",
                "input"=>array(
                    "T"=>"user",
                    "E"=>"register"
                )
            );
        }
        $this->session_data=$this->callCmd($cmd, $this->getSvcCfg());
        $this->session_update();
    }
     /**
     * user_update 
     * [T]= user
     * [E]= update
     */
    public function user_update() {
        if ($this->session_privacy != true) {
            return false;
        }
        $cmd=$this->getSvcCfg("command.update");
        if (empty($cmd)) {
            $cmd=array(
                "type"=>"serviceCommon",
                "module"=>"Auth",
                "name"=>"sqlite",
                "input"=>array(
                    "T"=>"user",
                    "E"=>"update"
                )
            );
        }
        $this->session_data=$this->callCmd($cmd, $this->getSvcCfg());
        $this->cookie_delete();
    }
     /**
     * user_update
     * [T]= user
     * [E]= delete
     */
    public function user_delete() {
        if ($this->session_privacy != true) {
            return false;
        }
        $cmd=$this->getSvcCfg("command.delete");
        if (empty($cmd)) {
            $cmd=array(
                "type"=>"serviceCommon",
                "module"=>"Auth",
                "name"=>"sqlite",
                "input"=>array(
                    "T"=>"user",
                    "E"=>"delete"
                )
            );
        }
        $this->session_data=$this->callCmd($cmd, $this->getSvcCfg());
        $this->session_update();
    }
}