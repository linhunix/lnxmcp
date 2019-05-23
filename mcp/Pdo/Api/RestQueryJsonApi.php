<?php

namespace LinHUniX\Pdo\Api;

use LinHUniX\Mcp\Model\mcpControllerModelClass;

/**
 * Classe dedicata alle chiamate api
 * (con il comando ApiReturn esegue la conversione in json della risposta )
 */
class RestQueryJsonApi  extends mcpControllerModelClass {
    /**
     * Dichiarazioni standard per il debug
     */
	protected function moduleInit(){
		$this->spacename=__NAMESPACE__;
		$this->classname=__CLASS__;
    }
    /**
     * Funzione recuperata da applicazione di gabriele per gestire 
     * la conversione in utf dei contenuti 
     */
    protected function utf8ize($result) {
        if (is_array($result)) {
            foreach ($result as $k => $v) {
                $result[$k] = $this->utf8ize($v);
            }
        } else if (is_string ($result)) {
            return utf8_encode($result);
        }
        return $result;
    }
    /**
     * operazione di loadOrdini 
     */
    protected function moduleCore(){
        $data=$_REQUEST;
        foreach ($this->argIn as $k=> $v) {
            $data[$k]=$v;
        }
        if (isset($data['mod'])==false) {
            return;
        }
        if (isset($data['q'])==false) {
            return;
        }
        $name=$data["q"];
        $module=$data["mod"];
        $res=$this->callCmd(
            array(
                "type"=>"queryJson",
                "name"=>$name,
                "module"=>$module
            ),
            $data
           );
        if (is_array($res)) {
            $this->argOut=$this->utf8ize($res);
        }
    }
}