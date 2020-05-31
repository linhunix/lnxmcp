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
    protected $auth_pwd;
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
        if (isset($this->auth_denied['*']) or isset($this->auth_denied['all']) ){
            return false;
        }
        if (isset($this->auth_allow['*']) or isset($this->auth_allow['all']) ){
            return true;
        }
        return false;

    }
    /**
     * setAuthSession
     */
    protected function setAuthSession($id=0,$user='nobody',$pwd='',$group='nogroup',$isadm=false,$allow=array(),$denied=array(),$gdpr=false,$lock=false,$email='',$phone='',$address='',$extra=array()){
        $this->auth_pwd=$pwd;
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
        $this->auth_ssave();
    }

    protected function setAuthArrSession($scopeauth=array()){
        $id=0;
        if (isset($scopeauth['id'])){
            if (is_numeric($scopeauth['id'])){
                $id=$scopeauth['id'];
            }
        }
        $user='nobody';
        if (isset($scopeauth['user'])){
            $user=$scopeauth['user'];
        }
        $pwd='';
        if (isset($scopeauth['pwd'])){
            $pwd=$scopeauth['pwd'];
        }
        $group='nobody';
        if (isset($scopeauth['group'])){
            $group=$scopeauth['group'];
        }
        $isadm=false;
        if (isset($scopeauth['isadm'])){
            if ($scopeauth['isadm']==true){
                $isadm=$scopeauth['isadm'];
            }
        }
        $allow=array();
        if (isset($scopeauth['allow'])){
            if (is_array($scopeauth['allow'])){
                $allow=$scopeauth['allow'];
            }
        }
        $denied=array();
        if (isset($scopeauth['denied'])){
            if (is_array($scopeauth['denied'])){
                $denied=$scopeauth['denied'];
            }
        }
        $gdpr=false;
        if (isset($scopeauth['gdpr'])){
            if ($scopeauth['gdpr']==true){
                $gdpr=$scopeauth['gdpr'];
            }
        }
        $lock=false;
        if (isset($scopeauth['lock'])){
            if ($scopeauth['lock']==true){
                $lock=$scopeauth['lock'];
            }
        }
        $email='';
        if (isset($scopeauth['email'])){
            $email=$scopeauth['email'];
        }
        $phone='';
        if (isset($scopeauth['phone'])){
            $phone=$scopeauth['phone'];
        }
        $address='';
        if (isset($scopeauth['address'])){
            $address=$scopeauth['address'];
        }
        $extra=array();
        if (isset($scopeauth['extra'])){
            if (is_array($scopeauth['extra'])){
                $extra=$scopeauth['extra'];
            }
        }
        $this->setAuthSession($id,$user,$pwd,$group,$isadm,$allow,$denied,$gdpr,$lock,$email,$phone,$address,$extra);
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
        if ($this->getIsAdm()==true){
            $this->auth_lock=false;
        }
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