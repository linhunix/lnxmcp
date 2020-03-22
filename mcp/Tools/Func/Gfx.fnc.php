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
 * lnxmcpFileList function.
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

/**
 * lnxmcpFileDelete function.
 *
 * @param array $scopein
 *                       -required category (for upload file )
 *                       -required files ias array
 *
 * @return array result
 */
function lnxmcpFileDelete($scopein)
{
    return lnxmcp()->controllerR('delete', false, $scopein, 'Upload', null, 'LinHUniX');
}

/**
 *  lnxmcpHtmlConvert() function 
 *  load a html text and convert the lnxmcp tag 
 *  @param string $htmltext
 *  @param array $scopein
 *  @param array $label 
 *  @return void/array result
 */
function lnxmcpHtmlConvert($htmltext,$scopein,$label=null) {
    return lnxmcp()->converTag($htmltext,$scopein,$label);
}