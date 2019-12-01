<?php

/**
 * lnxMcpMimeFile replace the mime_content_type.
 *
 * @param string $filename
 * @param string $defaultmime
 *
 * @return string content-type
 */
function lnxMcpMimeFile($filename, $defaultmime = null)
{
   return  \mcpOutput::getInstance()->mimeFile($filename,$defaultmime);
}

/**
 * linhunix External file load and converter.
 *
 * @param string $file
 * @param string $path    if is need
 * @param string $ext     with out the '.'
 * @param array $scopeIn array in
 * @param boolean $convert if need to  convert or  only load
 * @param boolean $runphp if need to  execute php or only exclude
 *
 * @return bool
 */
function lnxMcpExtLoad( $file, $path = '', $ext = null, $scopeIn = array(), $convert = true, $runphp=false)
{
    return \mcpOutput::getInstance()->loadExtFile($file,$path,$ext,$scopeIn,$convert,$runphp);
}

/**
 * LnxMcpRealEscape function is an alternative to  mysql_real_escape_string.
 *
 * @see https://stackoverflow.com/questions/1162491/alternative-to-mysql-real-escape-string-without-connecting-to-db
 *
 * @param string $value
 *
 * @return string
 */
function LnxMcpRealEscape($value)
{
    $search = array('\\',  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
    $replace = array('\\\\', '\\0', '\\n', '\\r', "\'", '\"', '\\Z');

    return str_replace($search, $replace, $value);
}
