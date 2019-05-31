<?php
/**
 * LinHUniX Web Application Framework.
 *
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 *
 * @version GIT:2018-v2
 */

namespace LinHUniX\Mcp\Tools;

/**
 * This class is used to manage the universal content manager.
 */
final class scheduler
{

    /////////////////////////////////////////////////////////////////////////////////////
    /// VARIABLES
    /////////////////////////////////////////////////////////////////////////////////////
    private $path;
    private $jsonpath;
    // ENABLE / DISABLE SCHEDULER
    private $enable;
    public function Off() {
        $this->enable=false;
    }
    public function On() {
        $this->enable=true;
    }
    // LIST JOB
    private $jobList;
    public function remove($job) {
        if (isset($this->jobList[$job])){
            unset ($this->jobList[$job]);
        }
    }
    public function add($job,array $commandarray) {
        if (!is_array($this->jobList)){
            $this->jobList=array();
        }
        $this->jobList[$job]=$commandarray;
    }
    // Background Mode 
    private $bgmode;
    public function getBgMode(){
        return $bgmode;
    }
    // Job Scope Array 
    private $jobscope;
    public function getScope(){
        return $jobscope;
    }
    public function get($var) {
        if (isset($this->jobscope[$var])){
            return $this->jobscope[$var];
        }
    }
    public function set($var,$val) {
        if (!is_array($this->jobscope)){
            $this->jobscope=array();
        }
        $this->jobscope[$var]=$val;
        if ($val='.'){
            unset( $this->jobscope[$var]);
        }
    }
    

    /////////////////////////////////////////////////////////////////////////////////////
    // PRIVATE FUNCTION - LOAD CONFIG
    /////////////////////////////////////////////////////////////////////////////////////

    /**
     * load config from mcp.
     */
    private function loadCfg()
    {
        $this->enable = lnxmcp()->getResource('job.enable');
        if ($this->enable != true) {
            $this->enable = false;
            return;
        }
        $this->path = lnxmcp()->getResource('job.path');
        if ($this->path == null) {
            $this->path = lnxmcp()->getResource('path');
        }
        $this->jsonpath = lnxmcp()->getResource('path.exchange');
        $this->jobList = lnxmcp()->getResource('job.list');
        $this->bgmode = lnxmcp()->getResource('job.bgmode');
    }



    /**
     * load config from mcp.
     */
    private function init()
    {
        switch ($this->bgmode) {
            default:
                $this->runScheduler();
                break;
        }
    }

    /**
     * load config from mcp.
     */
    private function runScheduler()
    {
        if (!is_array($this->jobdirect)) {
            $this->jobdirect = array();
        }
        $GLOBALS["LnxMcpJob"]=$this;
        while ($this->enable == true) {
            lnxPutJsonFile($this->jobscope,"scheduler", $this->jsonpath, "json");

        }
    }
    /**
     * runSequence inside actions.
     *
     * @param mixed $actions
     * @param mixed $scopeIn
     *
     * @return any $ScopeOut
     */
    public function runJobSequence()
    {
        $actionsSeq=$this->jobList;
        $scopeIn=$this->jobscope;
        if ($actionsSeq == null) {
            lnxmcp()->warning('sequence null!!');
            return $scopeIn;
        }
        foreach ($actionsSeq as $callname => $scopeCtl) {
            lnxmcp()->info('Sequence call app.'.$callname);
            $runstep=true;
            if (strstr($callname,"date|")) {
                $datein=date( "u|Y|m|d|H|i|w");
                $caldar=explode("|",$callname);
                $datear=explode("|",$datein);
                $i=0;
                foreach ($caldar as $calinfo) {
                    if ($i==0){
                        $i++;
                        continue;
                    }
                    if ($calinfo=="*") {
                        $i++;
                        continue;
                    }
                    if (strstr($calinfo,"/")!=false){
                        $calirr=$explode('/',$calinfo);
                        if (!($datear[$i] % intval($calirr[1]) )) {
                            $runstep=false;
                        }
                    }else {
                        if ($datear[$i] != intval($calinfo)) {
                            $runstep=false;
                        }
                    }
                    $i++;
                }
            }
            if ($runstep!=true) {
                continue;
            }
            if (!isset($scopeCtl['name'])) {
                $scopeCtl['name'] = $callname;
            }
            if (isset($scopeCtl['input'])) {
                foreach ($scopeCtl['input'] as $sik => $siv) {
                    $scopeIn[$sik] = $siv;
                }
            }
            $scopeIn[$callname] = lnxmcp()->runCommand($scopeCtl, $scopeIn);
        }
        return $scopeIn;
    }

    /**
     * scheduler __construct function.
     *
     * @param array  $scopein
     */
    public function __construct($scopein = null)
    {
        $this->jobscope = $scopein;
        if (!is_array($this->jobscope)) {
            $this->jobscope = array();
        }
        $this->loadCfg();
        if ($this->enable == true) {
            $this->init();
        }
    }
}
