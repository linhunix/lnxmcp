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
 *                       -required [allowlist] ext in the list is allowred
 *                      -required [allowfields] 
 *
 * @return array result
 */
function lnxmcpUpload($scopein)
{
    return lnxmcp()->controllerR('upload', false, $scopein, 'Upload', null, 'LinHUniX');
}

/**
 * lnxmcpUpload function.
 *
 * @param array $scopein
 *                       -required category (for upload file )
 *                       --[basename]
 *                       --[category]
 *                       -required [allowlist] ext in the list is allowred
 *
 * @return array result
 */
function lnxmcpFileList($scopein)
{
    return lnxmcp()->controllerR('list', false, $scopein, 'Upload', null, 'LinHUniX');
}