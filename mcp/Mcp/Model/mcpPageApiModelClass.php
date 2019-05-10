<?php
/**
 * Created by PhpStorm.
 * User: linhunix
 * Date: 9/4/2018
 * Time: 10:11 AM
 */
namespace LinHUniX\Mcp\Model;

use LinHUniX\Mcp\masterControlProgram;

class mcpPageApiModelClass extends mcpControllerModelClass
{
    /**
     * In this service class is premanaged the module core as reflection calling
     * so  inf asking an event this call the specific method if is present the order 
     *  type method
     *  name method
     *  type_name method
     *
     * @return void
     */
    protected function moduleCore()
    {
        $this->ApiStart();
        if ($this->Service != null ){
            if ($this->Service instanceof mcpServiceModelClass ) {
                $this->Service->runEvent("Api",$this->ClassName);
            }
        }
        if (isset($this->argIn["E"])) {
            if (method_exists($this, $this->argIn["E"])) {
                $this->$this->argIn["E"]();
            }
        }
        $this->ApiEnd();
    }
    /**
     * This a prepare execution method free to customize 
     */
    protected function ApiStart() {
        // EMPTY CLASS TO GET ALL EXTRA INFORMATION ABOUT 
    }
    /**
    * This a post execution method free to customize 
    */
   protected function ApiEnd() {
       // EMPTY CLASS TO GET ALL EXTRA INFORMATION ABOUT 
   }

}