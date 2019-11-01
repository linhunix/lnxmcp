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
    private $csvcategory;
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
        $this->csvcategory=$this->getCfg("app.csv.category");
        if ($this->csvcategory==''){
            $this->csvcategory="csv";
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
        $scopein['category']=$this->csvcategory;
        return \lnxmcpUpload($scopein);
    }

    public function csv_load() {
        if (!isset($this->argIn['file'])){
            $this->warning("No csv file selected!!! ");
            return false;
        }
        $data=array();
        $user_path = $this->getRes('path.userfile');
        $user_path .= DIRECTORY_SEPARATOR.$this->csvcategory;
        if (isset($this->argIn['path'])){
            $user_path=$this->argIn['path'];
        }
        $csvfile=$user_path.DIRECTORY_SEPARATOR.$this->argIn['file'];
        if (!file_exists($csvfile)){
            $this->warning(" csv file not found [".$csvfile."]!!! ");
            return false;
        }
        $headerload=false;
        $headerlist=null;
        if (isset($this->argIn["isHeader"])){
            $headerload=$this->argIn["isHeader"];
        }
        if (isset($this->argIn["headerList"])){
            $headerlist=$this->argIn["headerList"];
        }
        $rowc=0;
        if (($handle = fopen($csvfile, "r")) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($headerload==true){
                    $headerlist=$row;
                    $headerload=false;
                    continue;
                }
                if (!is_array($headerlist)){
                    $data[$rowc]=$row;
                    $rowc++;
                    continue;
                }
                $rown=array();
                $rowx=0;
                foreach($row as $field){
                    $fkey=$rowx;
                    if (isset($headerlist[$rowx])){
                        $fkey=$headerlist[$rowx];
                    }
                    $rown[$fkey]=$field;
                    $rowx++;
                }
                $data[$rowc]=$rown;
                $rowc++;
            }
            fclose($handle);
        }
        return $data;
    }
}
