<?php
/**
 * Created by VSCODE.
 * User: linhunix
 * Date: 15/9/2019
 * Time: 10:11 AM.
 */

namespace LinHUniX\Auth\Service;

use LinHUniX\Mcp\Model\mcpServiceModelClass;
use LinHUniX\Auth\Service\authCfgDriver;

class authService extends mcpServiceModelClass
{

    protected $authservice;

    //////////////////////////////////////////////////////////////////////
    // SERVICE INTEGRATION FUNCTION 
    //////////////////////////////////////////////////////////////////////

    protected function moduleSingleTon() {
        $sercfg=$this->getCfg("app.auth.service");
        if (is_array($sercfg)){
            $this->authservice=$this->callCmd(
                $sercfg,
                $this->argIn
            );
        }else{
            $this->authservice= new authcfgDriver($this->getMcp(),$this->argCfg,array());
        }
    }


    protected function moduleInit()
    {
        $this->spacename = __NAMESPACE__;
        $this->classname = __CLASS__;
        $this->setCommon("user","guest");
        $this->auth_sload();
    }


    //////////////////////////////////////////////////////////////////////
    // CUSTOM FUNCTION 
    //////////////////////////////////////////////////////////////////////

    /**
     * function modulesetup
     * default setup module
     * @return void
     */
    protected function auth_setup(){
        if ($this->authservice==null){
            $this->warning("authservice is null");
            return;
        }
        $this->authservice->auth_setup();
    }
    /**
     * function modulelogin
     * default login session
     * @return void
     */
    public function auth_login(){
        if ($this->authservice==null){
            $this->warning("authservice is null");
            return;
        }
        $this->authservice->auth_login();
    }
    /**
     * function modulelogin
     * default logout session
     * @return void
     */
    public function auth_logout(){
        if ($this->authservice==null){
            $this->warning("authservice is null");
            return;
        }
        $this->authservice->auth_logout();
    }
     /**
     * function auth_register
     * default create user session
     * @return void
     */
    public function auth_register(){
        if ($this->authservice==null){
            $this->warning("authservice is null");
            return;
        }
        $this->authservice->auth_register();
    }
     /**
     * function modulelogin
     * default delete user session
     * @return void
     */
    public function auth_unregister(){
        if ($this->authservice==null){
            $this->warning("authservice is null");
            return;
        }
        $this->authservice->auth_unregister();
    }
     /**
     * function modulelogin
     * default update user data session
     * @return void
     */
    public function auth_update(){
        if ($this->authservice==null){
            $this->warning("authservice is null");
            return;
        }
        $this->authservice->auth_update();
    }
    /**
     * function auth_recover
     * default revove - return password - forgot session
     * @return void
     */
    public function auth_recover(){
        if ($this->authservice==null){
            $this->warning("authservice is null");
            return;
        }
        $this->authservice->auth_recover();
    }
     /**
     * function auth_unluck
     * default unlock session
     * @return void
     */
    public function auth_unluck(){
        if ($this->authservice==null){
            $this->warning("authservice is null");
            return;
        }
        $this->authservice->auth_unluck();
    }
     /**
     * function auth_unluck
     * default unlock session
     * @return void
     */
    public function auth_luck(){
        if ($this->authservice==null){
            $this->warning("authservice is null");
            return;
        }
        $this->authservice->auth_luck();
    }
     /**
     * function auth_notify
     * default notify changes
     * @return void
     */
    public function auth_notify(){
        if ($this->authservice==null){
            $this->warning("authservice is null");
            return;
        }
        $this->authservice->auth_notify();
    }
    /**
     * function auth_sload
     * load session data 
     * @return void
     */
    public function auth_sload(){
        if ($this->authservice==null){
            $this->warning("authservice is null");
            return;
        }
        $this->authservice->auth_sload();
    }
    /**
     * function auth_ssave
     * save session data 
     * @return void
     */
    public function auth_ssave(){
        if ($this->authservice==null){
            $this->warning("authservice is null");
            return;
        }
        $this->authservice->auth_ssave();
    }

}