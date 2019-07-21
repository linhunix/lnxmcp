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
function lnxmcpChk($checkmenu = null)
{
    $mcpCheckFile = lnxmcp()->getCfg('mcp.path').'/../mcp_modules/Chk/Shell/mcpCheck.php';
    lnxmcp()->info('Try to load CheckModule:'.$mcpCheckFile);
    if (file_exists($mcpCheckFile)) {
        echo "load Check Env on $mcpCheckFile..\n";
        include_once $mcpCheckFile;
        echo "Run mcpCheck:\n";
        LinHUniX\McpModules\Chk\Shell\mcpCheck($checkmenu);
        echo "Check Complete!!\n";
    }
}
function lnxmcpAdm($defcmd = null) {
    $mcpAdminShell = lnxmcp()->getCfg('mcp.path').'/../mcp_modules/Adm/Shell/init.php';
    if (file_exists($mcpAdminShell)) {
        include_once $mcpAdminShell;
    }
}