<?php

function LnxUcm($scopein = null)
{
    lnxmcp()->ucm($scopein);
}
/**
 * lnxmcpUpload function.
 *
 * @param array $scopein
 * -required category (for upload file )
 */
function lnxmcpUpload($scopein)
{
    $mcpAdminModPath = lnxmcp()->getCfg('mcp.path').'/../mcp_modules/Upload/';
    lnxmcp()->setCfg('app.mod.path.LinHUniX.Upload', $mcpAdminModPath);
    lnxmcp()->Controller('init',false,$scopein,'Upload',null,'LinHUniX');
}