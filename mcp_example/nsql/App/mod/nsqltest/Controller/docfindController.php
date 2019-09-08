<?php
/**
** <DESCRIPTION>
**/
namespace nsqltest\Controller;
use LinHUniX\Mcp\Model\mcpBaseModelClass;
/**
 * docloadController
 */
class docfindController extends mcpBaseModelClass {
    /**
    * Ideally this method shuld be used to first esecution
    */
    protected function moduleInit(){
        $this->spacename=__NAMESPACE__;
        $this->classname=__CLASS__;
    }
    /**
    * Ideally this method shuld be used to insert
    * the model code and the other are to be used only as normal
    @return mixed
    */
    protected function moduleCore() {
        return $this->callCmd(
            array(
                "type"=>"serviceCommon",
                "module"=>"Nsql",
                "name"=>"nsql"
            ),
            array(
                "T"=>"doc",
                "E"=>"finddoc",
                "doc_var"=>$_REQUEST['doc_name'],
                "doc_val"=>$_REQUEST['doc_find']
            )        
        );
    }
    /**
    * Ideally this method shuld be used to insert
    * the model code and the other are to be used only as normal
    */
    protected function moduleSingleTon() {}
}