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
    protected $authGrp;
    protected $authadm;
    protected $authname;
    protected $authid;
    protected $authextra;
    protected $authallow;
    protected $authdenied;

    /**
     * function getGroup
     * @return string
     */
    public function getGroup(){
        return $this->authGrp;
    }

    /**
     * function getName
     * @return string
     */
    public function getName(){
        return $this->authGrp;
    }
    /**
     * function getId
     * @return int
     */
    public function getId(){
        return $this->authGrp;
    }
    /**
     * function getExtra
     * @return array
     */
    public function getExtra($name){
        if (($name==null) or ($name=='')){
            return $this->authextra;
        }
        if (isset($this->authextra[$name])){
            return $this->authextra[$name];
        }
        return null;
    }
    /**
     * function getIsAdm
     * @return boolean
     */
    public function getIsAdm(){
        return $this->authadm;
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
        if ($this->getIsAdm()==true){
            return true;
        }
        if (isset($this->authdenied[$role])){
            return false;
        }
        if (isset($this->authallow[$role])){
            return true;
        }
        if (isset($this->authdenied['*']) or isset($this->authdenied['all']) ){
            return false;
        }
        if (isset($this->authallow['*']) or isset($this->authallow['all']) ){
            return true;
        }
        return false;

    }
    /**
     * function modulesetup
     * default setup module
     * @return void
     */
    protected function run_setup(){
            /*** put jour code */
    }
    /**
     * function modulelogin
     * default login session
     * @return void
     */
    protected function run_login(){
            /*** put jour code */
    }
    protected function moduleSingleTon() {
        $this->run_setup();
    }


    protected function moduleInit()
    {
        $this->spacename = __NAMESPACE__;
        $this->classname = __CLASS__;
        $this->authGrp='nogroup';
        $this->authadm=false;
        $this->authname='nobody';
        $this->authid=0;
        $this->authextra=array();
        $this->authallow=array();
        $this->authdenied=array();
        $this->run_login();
        $this->getMcp()->setCommon("admin",$this->getIsAdm());    
        $this->getMcp()->setCommon("username",$this->getName());    
        $this->getMcp()->setCommon("userid",$this->getid());    
        $this->getMcp()->setCommon("groupname",$this->getGroup());    
    }
}