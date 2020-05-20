<?php

/**
 * LinHUniX Web Application Framework
 *
 * @author Andrea Morello <lnxmcp@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 */

namespace LinHUniX\Cron\Service;

use LinHUniX\Mcp\Model\mcpServiceModelClass;

class CronService extends mcpServiceModelClass {
    private $tasks;
    private $schedule;
    private $status;


    public function sync($statusIn=null)
    {
        if ($statusIn!=null){
            $this->status=$statusIn;
        }
        return $this->status;
    }
    /**
     *  function moduleInit() 
     */
    protected function moduleInit(){
        $this->spacename=__NAMESPACE__;
        $this->classname=__CLASS__;
    }

    /**
     * standard 1 shot user
     */
    protected function moduleSingleTon() {
        $this->schedule=$this->getRes('cron.sched');
        $this->tasks=$this->getRes('cron.tasks');
        if (!is_array($this->schedule)){
            $this->schedule=array();
        }
        if (!is_array($this->tasks)){
            $this->tasks=array();
        }
        $this->sync($this->argIn);
        $this->jobs_check();
    }

    protected function jobs_check(){
        if (isset($this->argIn['tasks'])){
            if (is_array($this->argIn['tasks'])){
                foreach ($this->argIn['tasks'] as $tk=>$tv){
                    $this->tasks[$tk]=$tv;
                }
            }
        }
        $subtask=scandir($this->argIn['spool']);
        foreach ($subtask as $file){
            if (strstr('.json',$file)!=false){
                $task=lnxGetJsonFile($this->argIn['spool'].'/'.$file);
                if (is_array($task)){
                    $this->tasks[$file]=$task;
                } else {
                    $this->tasks[$file]=array("tag"=>$task);
                }                
            }
        }
    }

    private function checkSchedule($sdata){
        $u=date('U');
        $Y=date('Y');
        $m=date('m');
        $d=date('d');
        $w=date('N');
        $h=date('G');
        $runjob=false;
        $first="1/1/1990";
        if(isset($sdata["first"])){
            $first=$sdata['start'];
        }
        $tfirst=strtotime($first);
        if ($tfirst<$u){
            $runjob=true;
        }
        if (isset($sdata['end'])){
            $tend=strtotime($sdata['end']);
            if ($u<$tend){
                $runjob=false;
            }
        }
        if ($runjob==true){
            if (isset($sdata["hours"])){
                if (!in_array($h,$sdata["hours"])){
                    $runjob==false;
                }
            }
        }
        if ($runjob==true){
            if (isset($sdata["days"])){
                if (!in_array($d,$sdata["days"])){
                    $runjob==false;
                }
            }
        }
        if ($runjob==true){
            if (isset($sdata["weeks"])){
                if (!in_array($w,$sdata["weeks"])){
                    $runjob==false;
                }
            }
        }
        if ($runjob==true){
            if (isset($sdata["months"])){
                if (!in_array($m,$sdata["months"])){
                    $runjob==false;
                }
            }
        }
        if ($runjob==true){
            if (isset($sdata["years"])){
                if (!in_array($Y,$sdata["years"])){
                    $runjob==false;
                }
            }
        }
        return $runjob;
    }

    protected function jobs_schedule(){
        foreach($this->schedule as $name=>$sdata){
            $runjob=$this->checkSchedule($sdata);
            if ($runjob==true){
                $this->status["jobrun"]='skd:'.$name;
                $this->status["jobstart"]=date('u');
                $in=$sdata;
                if (isset($sdata["in"])){
                    $in=$sdata["in"];
                }
                if (isset($sdata["ctl"])){
                    if ( is_array($sdata['ctl'])){
                        $this->schedule[$name]['out']=$this->callCmd(
                            $sdata["ctl"],
                            $in  
                        );
                        $this->status["status"]="done";
                        $this->status["jobend"]=date('u');
                        return true;
                    }
                }
            }
        }
        return false;
    }

    protected function jobs_tasks(){
        $spoolpath=$this->status['spool'];
        foreach($this->tasks as $name=>$sdata){
            if (! isset($sdata['status'])){
                $sdata['status']="ready";
            }
            if ($sdata['status']=='ready'){
                $this->status["jobrun"]='tsk:'.$name;
                $this->status["jobstart"]=date('u');
                $in=$sdata;
                if (isset($sdata["in"])){
                    $in=$sdata["in"];
                }
                if (! is_array($in)){
                    $in=array("in"=>$in);
                }
                $ctl=$name;
                if (isset($sdata['ctl'])){
                    $ctl=$sdata['ctl'];
                }
                if (! is_array($ctl)){
                    $ctl=array("type"=>'tag','name'=>$ctl);
                }
                $this->tasks[$name]['out']=$this->callCmd(
                    $ctl,
                    $in
                );
                $this->status["status"]="done";
                $this->status["jobend"]=date('u');
                $this->tasks[$name]['status']='done';
                lnxPutJsonFile($this->tasks[$name],$spoolpath.'/'.$name);
                return true;
            }
        }
        return false;
    }

    protected function jobs_exec()
    {
        $runjob=$this->jobs_schedule();
        if ($runjob==true){
            return true;
        }
        return $this->jobs_tasks();
    }
}
       