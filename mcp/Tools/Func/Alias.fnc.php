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
    return LinHUniX\Mcp\masterControlProgram::GetMcp();
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
    return lnxmcp()->runTag($tagname, $scopeIn);
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
