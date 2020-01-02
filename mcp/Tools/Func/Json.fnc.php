<?php

////////////////////////////////////////////////////////////////////////////////
//  DATA INTERACTION
////////////////////////////////////////////////////////////////////////////////
/**
 * linhunix json array converter.
 *
 * @param mixed $file
 * @param mixed $path if is need
 * @param mixed $ext  with out the '.'
 *
 * @return any json object converted as array
 */
function lnxGetJsonFile($file, $path = '', $ext = '')
{
    $jfile = $path;
    if ($jfile != '') {
        $jfile .= DIRECTORY_SEPARATOR;
    }
    $jfile.=$file;
    if ($ext != '') {
        $jfile .= '.'.$ext;
    }
    if (file_exists($jfile)) {
        try {
            lnxmcp()->info('lnxGetJsonFile:'.$jfile);

            return json_decode(file_get_contents($jfile), true);
        } catch (\ErrorException $e) {
            lnxmcp()->warning('lnxGetJsonFile>>file:'.$jfile.' and err:'.$e->get_message());

            return false;
        } catch (\Exception $e) {
            lnxmcp()->warning('lnxGetJsonFile>>file:'.$jfile.' and err:'.$e->get_message());

            return false;
        }
    } else {
        lnxmcp()->info('lnxGetJsonFile>>file:'.$jfile.' and err not found');
    }

    return null;
}
/**
 * linhunix json array converter.
 *
 * @param mixed $content
 * @param mixed $file
 * @param mixed $path    if is need
 * @param mixed $ext     with out the '.'
 *
 * @return bool
 */
function lnxDelJsonFile($file, $path = '', $ext = '', $expire = null)
{
    $jfile = $path;
    if ($jfile != '') {
        $jfile .= DIRECTORY_SEPARATOR;
    }
    $jfile.=$file;
    if ($ext != '') {
        $jfile .= '.'.$ext;
    }
    if (file_exists($jfile)) {
        if ($expire == null) {
            unlink($jfile);

            return true;
        }
        $dnow = date('U');
        $dfile = date('U', filemtime($jfile));
        $diff_file = $dnow - $dfile;
        if ($diff_file > intval($expire)) {
            unlink($jfile);

            return true;
        }

        return false;
    }
}
/**
 * linhunix json array converter.
 *
 * @param mixed $content
 * @param mixed $file
 * @param mixed $path    if is need
 * @param mixed $ext     with out the '.'
 *
 * @return bool
 */
function lnxPutJsonFile($content, $file, $path = '', $ext = '')
{
    $jfile = $path;
    if ($jfile != '') {
        $jfile .= DIRECTORY_SEPARATOR;
    }
    $jfile.=$file;
    if ($ext != '') {
        $jfile .= '.'.$ext;
    }
    if (!is_dir(dirname($jfile))) {
        lnxmcp()->warning('lnxPutJsonFile:'.dirname($jfile).' not exist!!');

        return false;
    }
    if (!is_writable(dirname($jfile))) {
        lnxmcp()->warning('lnxPutJsonFile:'.dirname($jfile).' not writable!!');

        return false;
    }
    try {
        lnxmcp()->info('lnxPutJsonFile:'.$jfile);

        return file_put_contents($jfile, json_encode($content, JSON_PRETTY_PRINT));
    } catch (\Exception $e) {
        lnxmcp()->warning('lnxPutJsonFile>>file:'.$jfile.' and err:'.$e->get_message());

        return false;
    }

    return null;
}
/**
 * linhunix json array converter.
 *
 * @param mixed $content
 * @param mixed $file
 * @param mixed $path    if is need
 * @param mixed $ext     with out the '.'
 *
 * @return bool
 */
function lnxUpdJsonFile($cntvar,$cntval, $file, $path = '', $ext = '') {
    $content=lnxGetJsonFile($file,$path,$ext);
    if (!is_array($content)) {
        $content=array();
    }
    if (!isset($content[$cntvar])) {
        $content[$cntvar]=0;
    }
    switch($cntval) {
    case "++":
        $val=intval($content[$cntvar]);
        $val++;
        $content[$cntvar]=$val;
        break;
    case "--":
        $val=intval($content[$cntvar]);
        $val--;
        $content[$cntvar]=$val;
        break;
    case ".":
        unset($content[$cntvar]);
        break;
    default:
        $content[$cntvar]=$cntval;
        break;
    }
    return lnxPutJsonFile($content,$file,$path,$ext);
}