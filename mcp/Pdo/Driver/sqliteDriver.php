<?php
namespace LinHUniX\Pdo\Driver;

use \PDO;
use LinHUniX\Pdo\Driver\pdoDriver;

/*
 * @copyright Content copyright to linhunix.com 2003-2018
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @version GIT:2018-v1
 * this new class implement the PDO mode to connect on databases;
 * @see [vendor]/mcp/Head.php  
 */
class sqliteDriver extends pdoDriver {


    function __construct (\LinHUniX\Mcp\masterControlProgram &$mcp, array $scopeCtl, array $scopeIn)
    {
        $path = $scopeIn["path"];
        $database = $scopeIn["database"];
        $scopeIn["dburlcon"]='sqllite:'.$path.DIRECTORY_SEPARATOR . $database;
        parent::__construct($mcp,$scopeCtl, $scopeIn);
    }
}