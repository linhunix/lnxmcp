<?php

namespace LinHUniX\Pdo\Driver;

use PDO;

/*
 * @copyright Content copyright to linhunix.com 2003-2018
 * @author Andrea Morello <lnxmcp@linhunix.com>
 * @version GIT:2018-v1
 * this new class implement the PDO mode to connect on databases;
 * @see [vendor]/mcp/Head.php
 */
class sqliteDriver extends pdoDriver
{
    public function __construct(\LinHUniX\Mcp\masterControlProgram &$mcp, array $scopeCtl, array $scopeIn)
    {
        $path = $scopeIn['path'];
        $database = $scopeIn['database'];
        $scopeIn['dburlcon'] = 'sqlite:'.$path.DIRECTORY_SEPARATOR.$database;
        parent::__construct($mcp, $scopeCtl, $scopeIn);
    }

    /**    SELECT name FROM sqlite_master WHERE type='table' ORDER BY name;
     */
    protected function listTable()
    {
        return $this->getTable("select * from sqlite_master where type='table';");
    }
}
