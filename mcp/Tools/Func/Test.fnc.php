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
    $modpath=lnxmcp()->getCfg('mcp.path.module');
    $mcpCheckFile = $modpath.'/Chk/Shell/mcpCheck.php';
    lnxmcp()->info('Try to load CheckModule:'.$mcpCheckFile);
    if (file_exists($mcpCheckFile)) {
        echo "load Check Env on $mcpCheckFile..\n";
        include_once $mcpCheckFile;
        echo "Run mcpCheck:\n";
        $res=LinHUniX\Chk\Shell\mcpCheck($checkmenu);
        echo "Check Complete!!\n";
        return $res;
    }
    return false;
}
/**
 * lnxmcpAdm function.
 *
 * @param string $defcmd
 */
function lnxmcpAdm($defcmd = null,$mode='Shell')
{
    lnxmcp()->setCommon('LnxAdmCmd', $defcmd);
    lnxmcp()->Controller('init',false,array('cmd'=>$defcmd,'mode'=>$mode),'LnxMcpAdm',null,'LinHUniX');
}
