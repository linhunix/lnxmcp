<?php
/**
 * LinHUniX Web Application Framework
 *
 * @author    Andrea Morello <lnxmcp@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version   GIT:2018-v2
 */

namespace LinHUniX\Mcp\Component;

use LinHUniX\Mcp\masterControlProgram;

/**
 * Core class for load modules
 */
class mcpArrayRecClass {
    private $arrayIn;
    private $arrayOut;
    private $funcArray;
    private $funcVal;
    public function __construct () {
        $this->reset();    
    }

    public function reset(){
        $this->arrayIn=array();
        $this->arrayOut=array();
        $this->funcArray=null;
        $this->funcVal=null;
    }
    public function run($argIn,$funcForArray,$funcForValue){
        $this->reset();
        if (!is_array($argIn)) {
            lnxmcp()->warning("Argin not Present!!");
            return null;
        }
        if ($funcForArray == null and $funcForValue==null){
            lnxmcp()->warning("Func not Presents!!");
            return null;
        }
        $this->arrayIn=$argIn;
        $this->funcArray=$funcForArray;
        $this->funcVal=$funcForValue;
        $this->recursive("init",$this->arrayIn);
        return $this->arrayOut;
    }

    private function recursive($arrkey,$arrToParse)
    {
        if (is_array($arrToParse)){
            foreach ($arrToParse as $ak=>$av){
                if (is_array($av)){
                    if (is_callable($this->funcArray)){
                        $this->arrayOut=$this->funcArray($this->arrayOut,$ak,$av);
                    }
                    $this->recursive($ak,$av);
                }else{
                    if (is_callable($this->funcVal)){
                        $this->arrayOut=$this->funcVal($this->arrayOut,$ak,$av);
                    }

                }
            }
        }
    }
}