<?php

/**
 * LinHUniX Web Application Framework
 *
 * @author Andrea Morello <lnxmcp@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 */

namespace LinHUniX\Auth\Service;

use LinHUniX\Auth\Model\mcpAuthServiceModelClass;

class authCfgService extends mcpAuthServiceModelClass {
      //////////////////////////////////////////////////////////////////////
    // CUSTOM FUNCTION 
    //////////////////////////////////////////////////////////////////////

    /**
     * function modulesetup
     * default setup module
     * @return void
     */
    protected function auth_setup(){
        /*** put jour code */
    }
    /**
     * function modulelogin
     * default login session
     * @return void
     */
    public function auth_login(){
            /*** put jour code */
    }
    /**
     * function modulelogin
     * default logout session
     * @return void
     */
    public function auth_logout(){
        $this->setAuthSession();
    }
     /**
     * function auth_register
     * default create user session
     * @return void
     */
    public function auth_register(){
        /*** put jour code */
    }
     /**
     * function modulelogin
     * default delete user session
     * @return void
     */
    public function auth_unregister(){
        /*** put jour code */
    }
     /**
     * function modulelogin
     * default update user data session
     * @return void
     */
    public function auth_update(){
        /*** put jour code */
    }
    /**
     * function auth_recover
     * default revove - return password - forgot session
     * @return void
     */
    public function auth_recover(){
        /*** put jour code */
    }
     /**
     * function auth_unluck
     * default unlock session
     * @return void
     */
    public function auth_unluck(){
        $this->auth_lock=false;
    }
     /**
     * function auth_unluck
     * default unlock session
     * @return void
     */
    public function auth_luck(){
        $this->auth_lock=true;
    }
     /**
     * function auth_notify
     * default notify changes
     * @return void
     */
    public function auth_notify(){
        $url=$this->getArgIn("url");
        $msg=$this->getArgIn("message");
        $mod=$this->getArgIn("module");
        if ($this->getAuthFor($mod."_notify")==true) {
            /// manage mail 
        }
    }
    /**
     * function auth_sload
     * load session data 
     * @return void
     */
    public function auth_sload(){

    }
    /**
     * function auth_ssave
     * save session data 
     * @return void
     */
    public function auth_ssave(){

    }


}