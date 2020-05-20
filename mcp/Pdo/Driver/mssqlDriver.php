<?php
namespace LinHUniX\Pdo\Driver;

//use \LinHUniX\Pdo\Driver\pdoDriver;

use \PDO;

/*
 * @copyright Content copyright to linhunix.com 2003-2018
 * @author Andrea Morello <lnxmcp@linhunix.com>
 * @version GIT:2018-v1
 * this new class implement the PDO mode to connect on databases;
 * @see [vendor]/mcp/Head.php  
 */

class mssqlDriver extends pdoDriver {


    function __construct (\LinHUniX\Mcp\masterControlProgram &$mcp, array $scopeCtl, array $scopeIn)
    {
        $hostname = $scopeIn["hostname"];
        $hostport = $scopeIn["hostport"];
        $database = $scopeIn["database"];
        $port="";
        if (!empty($hostport)){
            $port=";port=".$hostport;
        }
        $scopeIn["dburlcon"]='sqlsrv:Server=' . $hostname .$port. ';Database=' . $database;
        $scopeIn["options"]=array(PDO::ATTR_PERSISTENT => true);
        parent::__construct($mcp,$scopeCtl, $scopeIn);
    }

    protected function listTable(){
        return $this->getTable("SELECT * FROM sys.databases ");
    }

}