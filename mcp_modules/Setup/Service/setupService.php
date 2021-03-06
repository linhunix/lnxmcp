<?php

/**
 * LinHUniX Web Application Framework
 *
 * @author Andrea Morello <lnxmcp@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 */

namespace LinHUniX\Setup\Service;

use LinHUniX\Mcp\Model\mcpServiceModelClass;

class setupService extends mcpServiceModelClass {


    private $setupdb;
    private $setuplstdb;
    private $msg;
    /**
     *  function moduleInit() 
     */
    protected function moduleInit(){
        $this->spacename=__NAMESPACE__;
        $this->classname=__CLASS__;
        $this->msg='';
    }

    /**
     * standard 1 shot user
     */
    protected function moduleSingleTon() {
        $cfgdir=lnxmcp()->getCfg('app.path.config');
        try {
            if (!is_dir($cfgdir)){
                mkdir($cfgdir);
            }
            if (!is_dir($cfgdir)){
                LnxMcpExit($cfgdir.' is not ready!!!');
            }
            if (!file_exists($cfgdir.'/setup.json')){
                lnxPutJsonFile(
                    array(
                        'setup_init'=>date('U'),
                        'setup_config'=>array(),
                        'setup_require'=>array(),
                        'setup_done'=>array(),
                        'last_action'=>date('U'),
                        'logs_action'=>array()
                    ),
                    $cfgdir.'/setup.json'
                );
            }
            if (!file_exists($cfgdir.'/setup.list.json')){
                lnxPutJsonFile(
                    array(
                    ),
                    $cfgdir.'/setup.list.json'
                );
            }
            if (!file_exists($cfgdir.'/setup.json')){
                LnxMcpExit($cfgdir.'/setup.json is not ready!!!');
            }
            $this->setupdb=lnxGetJsonFile($cfgdir.'/setup.json');
            $this->setuplstdb=lnxGetJsonFile($cfgdir.'/setup.list.json');
        }catch( Exception $e){
            LnxMcpExit("Setup Error:".$e->getMessage());
        }
    }
    /**
     * 
     */
    private function writecfg($msg){
        $cfgdir=lnxmcp()->getCfg('app.path.config');
        $dateupd=date('U');
        $this->setupdb['last_action']=$dateupd;
        $this->setupdb['logs_action'][$dateupd]=$msg;
        lnxPutJsonFile(
            $this->setupdb,
            $cfgdir.'/setup.json'
        );
        lnxPutJsonFile(
            $this->setuplstdb,
            $cfgdir.'/setup.list.json'
        );
    }
    /**
     * 
     */
    private function chkreq($reqarray){
        $reqok=true;
        $require=array();
        if (isset($reqarray['false'])){
            $this->warning('the feature can not be managed!!!');
            return false;
        }
        foreach($reqarray as $rk=>$rv){
            switch($rv){
                case 'requireOK':
                    if (! isset($this->setupdb['setup_require'][$rk])){
                        $reqok=false;
                        $require[]=$rk;
                        $this->warning('the feature '.$rk.'is not installed (required)');
                    }
                break;
                case 'requireKO':
                    if ( isset($this->setupdb['setup_require'][$rk])){
                        $reqok=false;
                        $this->warning('the feature '.$rk.'is installed (conflict)');
                    }
                break;
                case 'featuresOK':
                    if (! isset($this->setupdb['setup_done'][$rk])){
                        $reqok=false;
                        $require[]=$rk;
                        $this->warning('the feature '.$rk.'is not installed (required)');
                    }
                break;
                case 'featuresKO':
                    if ( isset($this->setupdb['setup_done'][$rk])){
                        $reqok=false;
                        $this->warning('the feature '.$rk.'is installed (conflict)');
                    }
                break;
                case 'ConfigOK':
                    if (lnxmcp()->getCfg($rk)==null){
                        $reqok=false;
                        $this->warning('the config vars '.$rk.'is not present (required)');
                    }
                break;
                case 'ConfigKO':
                    if ( lnxmcp()->getCfg($rk)!=null){
                        $reqok=false;
                        $this->warning('the config vars'.$rk.'is present (conflict)');
                    }
                break;
                case 'SetupOK':
                    if (! isset($this->setupdb['setup_config'][$rk])){
                        $reqok=false;
                        $this->warning('the setup config vars '.$rk.'is not present (required)');
                    }
                break;
                case 'SetupKO':
                    if ( isset($this->setupdb['setup_config'][$rk])){
                        $reqok=false;
                        $this->warning('the setup config vars'.$rk.'is present (conflict)');
                    }
                break;
                default:
                    $reqok=false;
                    $this->warning('the value '.$rv.' request by '.$rk.' is not valid');
                break;
            }
        }
        return array(
            'sts'=>$reqok,
            'ref'=>$require
        );

    }
    private function xwarning($msg){
        $this->msg.='[Warn]'.$msg."\n";
        $this->warning($msg);
    }

    /**
     * 
     */
    public function setup_message(){
        return $this->msg;
    }

    /**
     * 
     */
    public function setup_message_reset(){
        $this->msg='';
    }
    /**
     * 
     */
    public function setup_add(){
        foreach(
            array(
                'name'=>'string',
                'require_add'=>'array',
                'require_del'=>'array',
                'remove'=>'array',
                'install'=>'array',
            ) as $k=>$v
        ){
            if (! $this->checkArgIn($k,$v)){
                $this->xwarning('the args '.$k.'is not presnet in ArgIn');
                return false;
            }
        }
        if (isset($this->setuplstdb[$this->argIn['name']])){
            $this->xwarning('the request '.$this->argIn['name']. "is already present!!");
            return true;
        }
        $this->setuplstdb[$this->argIn['name']]=$this->argIn;
        $this->writecfg('add features:'.$this->argIn['name']);
        return true;
    }
        
    /**
     * 
     */
    public function setup_cfgupd(){
        foreach(
            array(
                'name'=>'string',
                'value'=>'string'
            ) as $k=>$v
        ){
            if (! $this->checkArgIn($k,$v)){
                $this->xwarning('the args '.$k.'is not presnet in ArgIn');
                return false;
            }
        }
        if ($this->argIn['value']!='.'){
            $this->setupdb['setup_config'][$this->argIn['name']]=$this->argIn['value'];
        }else{
            unset($this->setupdb['setup_config'][$this->argIn['name']]);
        }
        $this->writecfg('add config:'.$this->argIn['name']);
        return true;
    }

    /**
     * 
     */
    public function setup_install(){
        foreach(
            array(
                'name'=>'string'
            ) as $k=>$v
        ){
            if (! $this->checkArgIn($k,$v)){
                $this->xwarning('the args '.$k.'is not presnet in ArgIn');
                return false;
            }
        }
        if (!isset($this->setuplstdb[$this->argIn['name']])){
            $this->xwarning('the feature '.$this->argIn['name'].'is not presnet in config_list');
            return false;
        }
        if (isset($this->setupdb['setup_done'][$this->argIn['name']])){
            $this->xwarning('the feature '.$this->argIn['name'].'is already installed');
            return false;
        }
        $reqres=$this->chkreq($this->setuplstdb[$this->argIn['name']]['require_add'] );
        if ($reqres['sts']==false){
            $this->xwarning('the feature '.$this->argIn['name'].'has solve the requirement list');
            return false;
        }
        $res=lnxmcp()->runCommand($this->setuplstdb[$this->argIn['name']]['install'],$this->setupdb['setup_config']);
        if ($res['return']==false){
            $this->xwarning('the feature '.$this->argIn['name'].' return with error');
            return false;
        }
        $this->setupdb['setup_done'][$this->argIn['name']]=date('U');
        foreach($reqres['ref'] as $refk){
            if (!isset($this->setupdb['setup_require'][$refk])){
                $this->setupdb['setup_require'][$refk]=arrau();
            }
            $this->setupdb['setup_require'][$refk][$this->argIn['name']]=date('U');
        }
        $this->writecfg('install features:'.$this->argIn['name']);
        return true;
    }

    /**
     * 
     */
    public function setup_remove(){
        foreach(
            array(
                'name'=>'string'
            ) as $k=>$v
        ){
            if (! $this->checkArgIn($k,$v)){
                $this->xwarning('the args '.$k.'is not presnet in ArgIn');
                return false;
            }
        }
        if (!isset($this->setuplstdb[$this->argIn['name']])){
            $this->xwarning('the feature '.$this->argIn['name'].'is not presnet in config_list');
            return false;
        }
        if (!isset($this->setupdb['setup_done'][$this->argIn['name']])){
            $this->xwarning('the feature '.$this->argIn['name'].'is not installed');
            return false;
        }
        $reqres=$this->chkreq($this->setuplstdb[$this->argIn['name']]['require_del'] );
        if ($reqres['sts']==false){
            $this->xwarning('the feature '.$this->argIn['name'].'has solve the requirement list');
            return false;
        }
        $res=lnxmcp()->runCommand($this->setuplstdb[$this->argIn['name']]['remove'],$this->setupdb['setup_config']);
        if ($res['return']==false){
            $this->xwarning('the feature '.$this->argIn['name'].' return with error');
            return false;
        }
        unset($this->setupdb['setup_done'][$this->argIn['name']]);
        foreach($reqres['ref'] as $refk){
            if (!isset($this->setupdb['setup_require'][$refk])){
                $this->setupdb['setup_require'][$refk]=arrau();
            }
            if (isset($this->setupdb['setup_require'][$refk][$this->argIn['name']])){
                unset($this->setupdb['setup_require'][$refk][$this->argIn['name']]);
            }
        }
        $this->writecfg('remove features:'.$this->argIn['name']);
        return true;
    }

    /**
     * 
     */
    public function setup_check(){
        foreach(
            array(
                'name'=>'string'
            ) as $k=>$v
        ){
            if (! $this->checkArgIn($k,$v)){
                $this->xwarning('the args '.$k.'is not presnet in ArgIn');
                return false;
            }
        }
        if (!isset($this->setuplstdb[$this->argIn['name']])){
            $this->xwarning('the feature '.$this->argIn['name'].'is not presnet in config_list');
            return false;
        }
        if (!isset($this->setuplstdb[$this->argIn['name']]['check'])){
            $this->xwarning('the feature '.$this->argIn['name'].'has not  check function');
            return false;
        }
        if (!isset($this->setupdb['setup_done'][$this->argIn['name']])){
            $this->xwarning('the feature '.$this->argIn['name'].'is not installed');
            return false;
        }
        lnxmcp()->setCommon('chk:'.$this->argIn['name'],$this->setuplstdb[$this->argIn['name']]['check']);
        
        $res=lnxmcpChk($this->setuplstdb[$this->argIn['name']]['remove']);
        if ($res==false){
            $this->xwarning('the check feature '.$this->argIn['name'].' return with error');
            return false;
        }
        return true;
    }
    /**
     * 
     */
    public function setup_batch(){
        foreach(
            array(
                'name'=>'string'
            ) as $k=>$v
        ){
            if (! $this->checkArgIn($k,$v)){
                $this->xwarning('the args '.$k.'is not presnet in ArgIn');
                return false;
            }
        }
        if (!isset($this->setuplstdb[$this->argIn['name']])){
            $this->xwarning('the feature '.$this->argIn['name'].'is not presnet in config_list');
            return false;
        }
        if (!isset($this->setuplstdb[$this->argIn['name']]['batch'])){
            $this->xwarning('the feature '.$this->argIn['name'].'has not  check function');
            return false;
        }
        if (!isset($this->setupdb['setup_done'][$this->argIn['name']])){
            $this->xwarning('the feature '.$this->argIn['name'].'is not installed');
            return false;
        }
        $res=lnxmcp()->runCommand($this->setuplstdb[$this->argIn['name']]['batch'],$this->setupdb['setup_config']);
        if ($res['return']==false){
            $this->xwarning('the Batch '.$this->argIn['name'].' return with error');
            return false;
        }        
        return true;
    }
    /**
     * 
     */
    public function setup_list(){
        $result=array();
        foreach($this->setuplstdb as $k=>$v){
            $result[$k]='Todo';
            if (isset($this->setupdb['setup_done'][$k])){
                $result[$k]=date('d/m/Y H:i:s',$this->setupdb['setup_done'][$k]);
            }
        }
        // $this->argOut=$result;
        // return true;
        return $result;
    }

    /**
     * 
     */
    public function setup_logs(){
        return $this->setupdb['logs_action'];
    }
}