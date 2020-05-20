<?php

namespace LinHUniX\Cron\Controller;

use LinHUniX\Mcp\Model\mcpBaseModelClass;

/**
 * LinHUniX Web Application Framework.
 *
 * @author Andrea Morello <lnxmcp@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 *
 * @version GIT:2018-v2
 */
class CronController extends mcpBaseModelClass
{
    private $cronservice;
    /**
     * moduleCore
     *
     * @return void
     */
    protected function moduleCore(){
        $cfgpath=$this->getRes("path.work");
        $cfgfile=$cfgpath."/Cron.status.json";
        $cronwrk=$cfgpath."/cron";
        $time=date('U');
        $cronstatus=lnxGetJsonFile($cfgfile);
        if (is_array($cronstatus)){
            if (isset($cronstatus['lastupdate'])){
                if (($time-$cronstatus)<180){
                    $this->debug(print_r($cronstatus));
                    LnxMcpExit("An other process is Alive!!");
                }
            }
        }
        if (!is_array($cronstatus)){
            $cronstatus=array();
        }
        $cronstatus["start"]=$time;
        $cronstatus["lastupdate"]=$time;
        $cronstatus["pid"]=getmypid();
        $cronstatus["file"]=$cfgfile;
        $cronstatus["path"]=$cfgpath;
        $cronstatus["spool"]=$cronwrk;
        $cronstatus["status"]="ready";
        if (!is_dir($cronwrk)){
            if (!mkdir($cronwrk)){
                LnxMcpExit("Can't create spool folder for cron!!");
            }
        }
        if (! is_writable($cronwrk)){
            LnxMcpExit("Can't write spool folder for cron!!");
        }
        if (!lnxPutJsonFile($cronstatus,$cfgfile)){
            LnxMcpExit("Can't save cron status file ");
        }
        $this->cronservice=$this->cronservice=$this->callCmd(
            array(
                "type"=>"serivce",
                "vendor"=>"LinHUniX",
                "module"=>"Cron",
                "name"=>"Cron",
                "isPreload"=>true
            ),
            $cronstatus
        );
        $this->runcron($cfgfile);
    }
    protected function runcron($cfgfile){
        while(true){
            $stscfg=lnxGetJsonFile($cfgfile);
            if ($stscfg!=getmypid()){
                LnxMcpExit("An other process start so i can't continues");
            }
            try {
                $this->cronservice->sync($stscfg);
                $this->cronservice->jobs_check();
                $this->cronservice->jobs_exec();
                $stscfg=$this->cronservice->sync();
            } catch(\Exception $e){
                $stscfg['status']='fail';
                $stscfg['error']=$e->getMessage();
            }
            $stscfg["lastupdate"]=date('U');
            lnxPutJsonFile($stscfg,$cfgfile);
            if ( $stscfg['status']=='fail'){
                LnxMcpExit("ERROR ON CRON:".$e->getMessage());
            }
        }
    }
}