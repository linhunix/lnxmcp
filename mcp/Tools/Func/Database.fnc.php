<?php

/**
 * Run Module as Check sequence.
 *
 * @param string $cfgvalue name of the Doctrine
 * @param string $modinit  Module name where is present the code and be load and initalized
 * @param string $path     path where present the basedirectory of the data
 * @param array  $scopeIn  Input Array with the value need to work
 * @param string $subcall  used if the name of the functionality ($callname) and the subcall are different
 *
 * @return array $ScopeOut
 */
function lnxmcpDbM($command = null, $element = null)
{
    if (empty($command)) {
        $command = 'help';
    }
    if (empty($element)) {
        $element = null;
    }
    $mcpCheckFile = lnxmcp()->getCfg('mcp.path').'/../mcp_modules/DbMig/Shell/mcpDbMigrate.php';
    lnxmcp()->info('Try to load DbMigrateModule:'.$mcpCheckFile);
    if (file_exists($mcpCheckFile)) {
        echo "load DbMigrate Env on $mcpCheckFile..\n";
        include_once $mcpCheckFile;
        echo "Run DbMigrate:$command\n";
        new LinHUniX\McpModules\DbMig\Shell\mcpDbMigrate($command, $element);
        echo "DbMigrate Complete!!\n";
    }
}
/**
 * Run Nsql Module
 *
 * @param string $action   Name of the event 
 * @param array  $scopeIn  Input Array with the value need to work
 * @param string $table    The name of the table if need
 *
 * @return array $ScopeOut 
 */
function lnxmcpNsql($action,$scopeIn=null,$table=null){
    if (!is_array($scopeIn)){
        $scopeIn=array();
    }
    $scopeIn["T"]="doc";
    $scopeIn["E"]=$action;
    if ($table!=null){
        $scopeIn["table"]=$table;
    }
    return lnxmcp()->RunCommand(
        array(
            "type"=>"serviceCommonReturn",
            "module"=>"Nsql",
            "vendor"=>"LinHUniX",
            "name"=>"nsql"
        ),
        $scopeIn
    );
}
/**
 * Run Nsql Ram Module
 *
 * @param string $table   The name of the table if need
 * @param string $mode    The mode level
 * @param string $action   Name of the event 
 * @param array  $scopeIn  Input Array with the value need to work
 *
 * @return array $ScopeOut 
 */
function lnxmcpRamDb($table,$mode,$action,$scopeIn=array()){
    if (!is_array($scopeIn)){
        $scopeIn=array();
    }
    $scopeIn["T"]=$mode;
    $scopeIn["E"]=$action;
    if ($table!=null){
        $scopeIn["table"]=$table;
    }
    return lnxmcp()->RunCommand(
        array(
            "type"=>"serviceCommonReturn",
            "module"=>"Nsql",
            "vendor"=>"LinHUniX",
            "name"=>"nsqlRam"
        ),
        $scopeIn
    );
}
/**
 * Run Csv Module
 *
 * @param string $action   Name of the event 
 * @param array  $scopeIn  Input Array with the value need to work
 * @param string $table    The name of the table if need
 *
 * @return array $ScopeOut 
 */
function lnxmcpCsv($action,$scopeIn=null,$csv='default',$table=null){
    if (!is_array($scopeIn)){
        $scopeIn=array();
    }
    $scopeIn["T"]="csv";
    $scopeIn["E"]=$action;
    $scopeIn["csv"]=$csv;
    if ($table!=null){
        $scopeIn["table"]=$table;
    }
    return lnxmcp()->RunCommand(
        array(
            "type"=>"serviceCommonReturn",
            "module"=>"Csv",
            "vendor"=>"LinHUniX",
            "name"=>"csv"
        ),
        $scopeIn
    );
}

/**
 * Run Lsql Module
 *
 * @param string $action   Name of the event 
 * @param array  $scopeIn  Input Array with the value need to work
 * @param string $table    The name of the table if need
 *
 * @return array $ScopeOut 
 */
function lnxmcpLsql($action,$scopeIn=null,$table=null){
    if (!is_array($scopeIn)){
        $scopeIn=array();
    }
    $scopeIn["T"]="log";
    $scopeIn["E"]=$action;
    if ($table!=null){
        $scopeIn["table"]=$table;
    }
    return lnxmcp()->RunCommand(
        array(
            "type"=>"serviceCommonReturn",
            "module"=>"Lsql",
            "vendor"=>"LinHUniX",
            "name"=>"nsql"
        ),
        $scopeIn
    );
}

/**
 * Run Lsql Module
 *
 * @param string $app   Name of the app that be tracked
 * @param string $user   Name ot the user make action
 * @param int $level   level of tracking log  0 low debug 50 normal 100 fatal
 * @param array  $data  Input Array with the value need to work
 * @param string $table    The name of the table if need
 *
 * @return array $ScopeOut 
 */
function lnxmcpTrace($app,$user,$data,$level=50,$table=null){
    $scopeIn=array();
    $scopeIn["T"]="log";
    $scopeIn["E"]='write';
    $scopeIn['app']=$app;
    $scopeIn['user']=$user;
    $scopeIn['logdata']=$data;
    $scopeIn['level']=$level;
    if ($table!=null){
        $scopeIn["table"]=$table;
    }
    return lnxmcp()->RunCommand(
        array(
            "type"=>"serviceCommonReturn",
            "module"=>"Lsql",
            "vendor"=>"LinHUniX",
            "name"=>"nsql"
        ),
        $scopeIn
    );

}