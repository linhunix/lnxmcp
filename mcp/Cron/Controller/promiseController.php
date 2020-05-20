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
class promiseController extends mcpBaseModelClass
{
    private function newPromiseId(){
        return "promise-".date("U");
    }
    /**
     * moduleCore
     *
     * @return void
     */
    protected function moduleCore(){
        $cfgpath=$this->getRes("path.work");
        $cronwrk=$cfgpath."/cron";
        $promise=null;
        $reqcmd=null;
        $status=null;
        $waitcmd=null;
        $donecmd=null;
        if (isset($this->argIn['promise'])){
            $promise=$this->argIn['promise'];
        }
        if (isset($this->argIn['promisecmd'])){
            $reqcmd=$this->argIn['promisecmd'];
        }
        if ($reqcmd==null and $promise ==null) {
            return false;
        }
        if (isset($this->argIn['waitcmd'])){
            $waitcmd=$this->argIn['waitcmd'];
        }
        if (isset($this->argIn['donecmd'])){
            $donecmd=$this->argIn['donecmd'];
        }
        ///case new promise
        if ($promise==null and is_array($reqcmd)){
            $promise=$this->newPromiseId();
            $task=array(
                "name"=>$promise,
                "status"=>"ready",
                "ctl"=>$reqcmd,
                "in"=>$this->argIn
            );
            $file=$cronwrk.'/'.$promise."json";
            lnxPutJsonFile($task,$file);
        }
        $file=$cronwrk.'/'.$promise."json";
        $status=lnxGetJsonFile($file);
        if (!is_array($status)){
            $status=array(
                "status"=>"error",
                "out"=>$status
            );
        }        
        if (isset($status['status'])){
            $status['status']='error';
        }
        $this->setCommon($promise,$status);
        if ($status['status']=='done' or $status['status']=='error' ){
            lnxDelJsonFile($file);
            if (is_array($donecmd)){
                return $this->callCmd(
                    $donecmd,
                    $status
                );
            }
        }
        if (is_array($waitcmd)){
            return $this->callCmd(
                $waitcmd,
                $status
            );
        }
        $this->argOut=$status;
        return $status;
    }
}