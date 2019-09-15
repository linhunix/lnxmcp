<?php
/**
 * Created by VSCODE.
 * User: linhunix
 * Date: 15/9/2019
 * Time: 10:11 AM.
 */

namespace LinHUniX\Auth\Model;

use LinHUniX\Mcp\Model\mcpServiceModelClass;

class mcpAuthServiceModelClass extends mcpServiceModelClass
{
    protected $auth_grp;
    protected $auth_adm;
    protected $auth_name;
    protected $auth_id;
    protected $auth_gdpr;
    protected $auth_allow;
    protected $auth_denied;
    protected $auth_lock;
    protected $auth_email;
    protected $auth_phone;
    protected $auth_address;
    protected $auth_extra;

    /**
     * function getGroup
     * @return string
     */
    public function getGroup(){
        return $this->auth_grp;
    }

    /**
     * function getName
     * @return string
     */
    public function getName(){
        return $this->auth_name;
    }
    /**
     * function getEmail
     * @return string
     */
    public function getEmail(){
        return $this->auth_email;
    }
    /**
     * function getPhone
     * @return string
     */
    public function getPhone(){
        return $this->auth_phone;
    }
    /**
     * function getGdprConsent
     * @return boolean
     */
    public function getGdprConsent(){
        return $this->auth_gdpr;
    }
    /**
     * function getAddress
     * @return string
     */
    public function getAddress(){
        return $this->auth_address;
    }
    /**
     * function getId
     * @return int
     */
    public function getId(){
        return $this->auth_id;
    }
    /**
     * function getExtra
     * @return array
     */
    public function getExtra($name=null){
        if (($name==null) or ($name=='')){
            return $this->auth_extra;
        }
        if (isset($this->auth_extra[$name])){
            return $this->auth_extra[$name];
        }
        return null;
    }
    /**
     * function getIsAdm
     * @return boolean
     */
    public function getIsAdm(){
        return $this->auth_adm;
    }
    /**
     * function getIsLucked
     * @return boolean
     */
    public function getIsLocked(){
        return $this->auth_lock;
    }
    /**
     * function getAuthFor
     * @param string $role
     * @return boolean
     */
    public function getAuthFor($rule){
        if($rule='' or $rule==null){
            return false;
        }
        if ($this->getIsLocked()==true){
            return false;
        }
        if ($this->getIsAdm()==true){
            return true;
        }
        if (isset($this->auth_denied[$role])){
            return false;
        }
        if (isset($this->auth_allow[$role])){
            return true;
        }
        if (isset($this->authd_enied['*']) or isset($this->auth_denied['all']) ){
            return false;
        }
        if (isset($this->auth_allow['*']) or isset($this->auth_allow['all']) ){
            return true;
        }
        return false;

    }
    /**
     * 
     */
    protected function setAuthSession($id=0,$user='nobody',$group='nogroup',$isadm=false,$allow=array(),$denied=array(),$gdpr=false,$lock=false,$email='',$phone='',$address='',$extra=array()){
        $this->auth_id=$id;
        $this->getMcp()->setCommon("userid",$this->getid());    
        $this->auth_name=$user;
        $this->getMcp()->setCommon("username",$this->getName());    
        $this->auth_grp=$group;
        $this->getMcp()->setCommon("groupname",$this->getGroup());    
        $this->auth_adm=$isadm;
        $this->getMcp()->setCommon("admin",$this->getIsAdm());    
        $this->auth_gdpr=$gdpr;
        $this->getMcp()->setCommon("gdpr_consent",$this->getGdprConsent());    
        $this->auth_lock=$lock;
        $this->getMcp()->setCommon("userlocked",$this->getIsLocked());    
        $this->auth_allow=$allow;
        $this->auth_denied=$denied;
        $this->auth_email=$email;
        $this->auth_phone=$phone;
        $this->auth_address=$address;
        $this->auth_extra=$extra;
        $this->getMcp()->setCommon("useremail",'');    
        $this->getMcp()->setCommon("userphone",'');    
        $this->getMcp()->setCommon("useraddress",'');    
        $this->getMcp()->setCommon("userextra",array());    
        if ($this->getGdprConsent()==true){
            $this->getMcp()->setCommon("useremail",$this->getEmail());    
            $this->getMcp()->setCommon("userphone",$this->getPhone());    
            $this->getMcp()->setCommon("useraddress",$this->getAddress());    
            $this->getMcp()->setCommon("userextra",$this->getExtra());    
        }
    }



    //////////////////////////////////////////////////////////////////////
    // SERVICE INTEGRATION FUNCTION 
    //////////////////////////////////////////////////////////////////////

    protected function moduleSingleTon() {
        $this->auth_setup();
    }


    protected function moduleInit()
    {
        $this->spacename = __NAMESPACE__;
        $this->classname = __CLASS__;
        $this->setAuthSession();
        $this->auth_login();
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
     * default login session
     * @return void
     */
    public function auth_register(){
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

}