<?php

/**
 * LinHUniX Web Application Framework
 *
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 */

namespace LinHUniX\Csv\Service;

use LinHUniX\Mcp\Model\mcpServiceModelClass;

class csvService extends mcpServiceModelClass {


    private $csvtable;
    private $csvfolder;
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
       
        $this->csvtable=$this->getCfg("app.csv.dbtable");
        if ($this->csvtable==''){
            $this->csvtable="lnxcsvdata";
        }
        $this->csvfolder=$this->getCfg("app.csv.folder");
        if ($this->csvtable==''){
            $this->csvtable="csv";
        }
        $this->nsql("tableInit");
    }

    /**
     *  PROTECTED FUNCION
     *  @param string $action
     *  @param array $data
     *  @return array 
     */
    protected function nsql($action,$data=array()){
        return \lnxmcpNsql($action,$data,$this->csvtable);
    }

    /**
     *  PROTECTED FUNCION
     *  @param string $action
     *  @param array $data
     *  @return array 
     */
    protected function upload($scopein=array()){
        if (!is_array($scopein)) {
            $scopein=array();
        }
        $scopein['category']=$this->csvfolder;
        return \lnxmcpUpload($scopein);
    }
}
