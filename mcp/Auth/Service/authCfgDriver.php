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
        $this->CfgUser=$this->getCfg('app.auth.users');
    }
    /**
     * function modulelogin
     * default login session
     * @return void
     */
    public function auth_login(){
        $user=$this->getArgIn('user');
        $pass=$this->getArgIn('pass');
        $pmd5=md5($pass);
        if(isset($this->CfgUser[$user])) {
            if($this->CfgUser[$user]['pwd']==$pmd5){
                $this->setAuthArrSession($this->CfgUser[$user]);
                if ($this->getIsLocked()==true){
                    $this->auth_logout();
                }
            }
        }
    }
    /**
     * function auth_sload
     * load session data 
     * @return void
     */
    public function auth_sload(){
        if ($_SESSION['auth_user']){
            $user=$_SESSION['auth_user'];
            $this->setAuthArrSession($this->CfgUser[$user]);
            if ($this->getIsLocked()==true){
                $this->auth_logout();
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