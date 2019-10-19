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
    if (class_exists('finfo')) {
        $result = new \finfo();
        if (is_resource($result) === true) {
            return $result->file($filename, FILEINFO_MIME_TYPE);
        }
    } elseif (function_exists('mime_content_type')) {
        return \mime_content_type($filename);
    }
    if ($defaultmime != null) {
        return $defaultmime;
    }

    return 'application/octet-stream';
}

/**
 * linhunix json array converter.
 *
 * @param mixed $content
 * @param mixed $file
 * @param mixed $path    if is need
 * @param mixed $ext     with out the '.'
 * @param mixed $scopeIn array in
 * @param mixed $convert if need to  convert or  only load
 *
 * @return bool
 */
function lnxMcpExtLoad($file, $path = '', $ext = null, $scopeIn = array(), $convert = true)
{
    switch ($path) {
    case 'app.path':
        $path = lnxmcp()->getResource('path');
        break;
    case 'app.path.module':
        $path = lnxmcp()->getResource('path.module');
        break;
    case 'app.path.template':
        $path = lnxmcp()->getResource('path.template');
        break;
    case 'mcp.path.root':
        $path = lnxmcp()->getCfg('mcp.path.root');
        break;
    case 'mcp.path':
        $path = lnxmcp()->getCfg('mcp.path');
        break;
    case 'app.path.workjob':
        $path = lnxmcp()->getResource('path.workjob');
        break;
    case 'app.path.exchange':
        $path = lnxmcp()->getResource('path.exchange');
        break;
    case 'app.path.language':
        $path = lnxmcp()->getResource('path.language');
        break;
    }
    $hfile = realpath($path);
    if ($hfile == '') {
        $hfile = $path;
    }
    lnxmcp()->debugvar('lnxMcpExtLoad', 'path', $path);

    if ($hfile != '') {
        $hfile .= DIRECTORY_SEPARATOR.$file;
    }
    if (!empty($ext)) {
        $hfile .= '.'.$ext;
    }
    if (!file_exists($hfile)) {
        $app_path = lnxmcp()->getResource('path');
        $hfile = $app_path.DIRECTORY_SEPARATOR.$hfile;
    }
    lnxmcp()->info('lnxMcpExtLoad try to load and convert :'.$hfile);
    if (file_exists($hfile)) {
        $mime = 'text/html';
        switch ($ext) {
        case 'tpl':
        case 'html':
            break;
        case 'css':
            $mime = 'text/css';
            break;
        case 'js':
            $mime = 'text/js';
            break;
        case 'jpg':
        case 'jpg':
            $mime = 'image/jpg';
            break;
        case 'gif':
            $mime = 'image/gif';
            break;
        case 'png':
            $mime = 'image/png';
            break;
        default:
            $mime = \lnxMcpMimeFile($hfile);
        }
        lnxmcp()->header('Content-Type: '.$mime, false, 200);
        try {
            if ($convert == true) {
                lnxmcp()->info('lnxMcpExtLoad:(with Convert)'.$hfile);

                return lnxmcp()->converTag(file_get_contents($hfile), $scopeIn);
            } else {
                lnxmcp()->info('lnxMcpExtLoad:(without Convert)'.$hfile);

                return file_get_contents($hfile);
            }
        } catch (\Exception $e) {
            lnxmcp()->warning('lnxMcpExtLoad>>file:'.$hfile.' and err:'.$e->getMessage());

            return false;
        }
    } else {
        lnxmcp()->info('lnxHtmlPage>>file:'.$hfile.' and not found');
    }

    return null;
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
