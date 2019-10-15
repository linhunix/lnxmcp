<?php

function LnxUcm($scopein = null)
{
    lnxmcp()->ucm($scopein);
}
/**
 * lnxmcpUpload function.
 *
 * @param array $scopein
 *                       -required category (for upload file )
 *                       -required fileconvert:
 *                       --[rand]
 *                       --[basename]
 *                       --[filetype]
 *                       --[field]
 *                       --[category]
 *                       --- all scopein field
 *                       -required allowlist ext in the list is allowred
 *
 * @return array result
 */
function lnxmcpUpload($scopein)
{
    $mcpAdminModPath = lnxmcp()->getCfg('mcp.path').'/../mcp_modules/Upload/';
    lnxmcp()->setCfg('app.mod.path.LinHUniX.Upload', $mcpAdminModPath);

    return lnxmcp()->Controller('init', false, $scopein, 'Upload', null, 'LinHUniX');
}
