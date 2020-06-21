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

class authCfgDriver extends mcpAuthServiceModelClass {
    protected $CfgUser;
      //////////////////////////////////////////////////////////////////////
    // CUSTOM FUNCTION 
    //////////////////////////////////////////////////////////////////////

    /**
     * function modulesetup
     * default setup module
     * @return void
     */
    protected function auth_setup(){
        $this->debug('load auth user profiles');
        $this->CfgUser=$this->getCfg('app.auth.users');
    }
    /**
     * function modulelogin
     * default login session
     * @return void
     */
    public function auth_login(){
        $this->debug(print_r($this->argIn,1));
        if(!is_array($this->argIn)){
            $this->argIn=$_REQUEST;
        }
        $user=$this->argIn['user'];
        $pass=$this->argIn['pass'];
        $pmd5=md5($pass);
        $this->debug('user:'.$user);
        $this->debug('pass:'.$pass);
        $this->debug('pmd5:'.$pmd5);
        if(isset($this->CfgUser[$user])) {
            if($this->CfgUser[$user]['pwd']==$pmd5){
                $this->debug(print_r($this->CfgUser[$user],1));
                $this->setAuthArrSession($this->CfgUser[$user]);
                if ($this->getIsLocked()==true){
                    $this->auth_logout();
                }
            }else{
                $this->debug('user and pass ko');
            }
        }else{
            $this->debug('user ko');
        }
    }
    /**
     * function auth_sload
     * load session data 
     * @return void
     */
    public function auth_sload(){
        if (isset($_SESSION['auth_user'])){
            $this->debug('check session:'.$_SESSION['auth_user']);
            $user=$_SESSION['auth_user'];
            if (isset($this->CfgUser[$user])){
                $this->setAuthArrSession($this->CfgUser[$user]);
                if ($this->getIsLocked()==true){
                    $this->auth_logout();
                }
                }else{
                    $this->debug("session is null,try to login");
                    $this->auth_login();
                }
            }
        
    }
    /**
     * function auth_ssave
     * save session data 
     * @return void
     */
    public function auth_ssave(){
        $_SESSION['auth_user']=$this->auth_name;
    }


}