<?php

////////////////////////////////////////////////////////////////////////////////
// MAIN ALIAS
////////////////////////////////////////////////////////////////////////////////
/**
 * lnxmcp.
 *
 * @return mastercontrolprogram
 */
function lnxmcp()
{
    if (isset($GLOBALS['mcp'])) {
        return $GLOBALS['mcp'];
    } else {
        error_log("FATAL ERROR - lnxmcp is NOT SETTED!!! \n");
        foreach (debug_backtrace() as $errarr) {
            error_log('-> '.$errarr['file'].' : '.$errarr['line'].' <br>');
        }

        return null;
    }
}

/**
 * LinHUnix Master Control Program
 * Fast Tag caller.
 *
 * @param mixed $tagname
 * @param mixed $scopein
 */
function lnxMcpTag($tagname, array $scopeIn = array())
{
    lnxmcp()->runTag($tagname, $scopeIn);
}
/**
 * LinHUnix Master Control Program
 * Fast Command caller.
 *
 * @param array $scopeCtl
 * @param array $scopein
 *
 * @return mixed $scopeout
 */
function lnxMcpCmd(array $scopeCtl, array $scopeIn = array())
{
    return lnxmcp()->runCommand($scopeCtl, $scopeIn);
}
/**
 * lnxMcpVersion  function.
 *
 * @return string version
 */
function lnxMcpVersion()
{
    return lnxmcp()->getCfg('mcp.ver');
}
