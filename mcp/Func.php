<?php

////////////////////////////////////////////////////////////////////////////////
// ERROR/CONFIG
////////////////////////////////////////////////////////////////////////////////
/**
 * Exit with closure procedure.
 *
 * @param mixed $message
 */
function LnxMcpExit($message = '')
{
    lnxmcp()->info('DumpAndExit:'.$message);
    lnxmcp()->runTag('Exit');
    exit();
}
if (function_exists('DumpAndExit') != true) {
    /**
     * DumpAndExit.
     *
     * @param mixed $message
     */
    function DumpAndExit($message = '')
    {
        lnxmcp()->info('DumpAndExit:'.$message);
        lnxmcp()->runTag('Exit');
        foreach (debug_backtrace() as $row => $debug) {
            if (is_array($debug)) {
                foreach ($debug as $drow => $ddebug) {
                    lnxmcp()->debug('['.$drow.']>>'.print_r($ddebug, 1));
                }
            }
        }
        exit();
    }
    /**
     * this version has only the error log call because is work when is present a big issue.
     *
     * @param string $message
     * @param bool   $exit
     */
    function DumpOnFatal($message, $exit = false)
    {
        lnxmcp()->runTag('Fatal');
        lnxmcp()->runTag('Exit');
        echo $message;
        foreach (debug_backtrace() as $errarr) {
            error_log('-> '.$errarr['file'].' : '.$errarr['line'].' <br>');
        }
        foreach (get_included_files() as $filename) {
            error_log("Load: $filename");
        }
        error_log('FATAL ERROR - lnxmcp is NOT SETTED!!! ');
        error_log(debug_print_backtrace());
        if ($exit == true) {
            exit(1);
        }
    }
}

////////////////////////////////////////////////////////////////////////////////
// AUTOLOAD/CONFIG
////////////////////////////////////////////////////////////////////////////////
/**
 * A basic autoload implementation that should be compatible with PHP 5.2.
 *
 * @author pmg
 */
function legacyAutoload($className)
{
    global $autoLoadFolders;
    $className = str_replace('/LinHUniX/', '/', $className);
    foreach ($autoLoadFolders as $folder) {
        $classPath = $folder.DIRECTORY_SEPARATOR.$className.'.php';
        if (file_exists($classPath)) {
            require_once $classPath;

            return true;
        }
    }

    return false;
}

/**
 * A basic autoload implementation that should be compatible with PHP 5.2.
 *
 * @author pmg
 */
function selfAutoLoad($srcPath)
{
    global $autoLoadFolders;
    $srcPath = realpath($srcPath);
    $scannedItems = scandir($srcPath);
    foreach ($scannedItems as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        if (is_dir($folder = $srcPath.DIRECTORY_SEPARATOR.$item)) {
            $autoLoadFolders[] = $folder;
        }
    }
    spl_autoload_register('legacyAutoload', true);
}

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
        $jfile .= DIRECTORY_SEPARATOR.$file;
    }
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
function lnxPutJsonFile($content, $file, $path = '', $ext = '')
{
    $jfile = $path;
    if ($jfile != '') {
        $jfile .= DIRECTORY_SEPARATOR.$file;
    }
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
 * lnxCacheCtl create a cache version of the specific bloc.
 *
 * @param string $name         name of the cache object
 * @param int    $expire       expire time
 * @param array  $alt_sequence altenative sequence use to run code
 * @param array  $alt_scopein  alternative input use to run code
 *
 * @return array
 */
function lnxCacheCtl($name, $expire = 3600, $alt_sequence, $alt_scopein)
{
    $cachepath = lnxmcp()->getResource('path.cache');
    $cachedate = date('U');
    $cacheobject = lnxGetJsonFile($name, $cachepath, 'json');
    $iscacheok = false;
    $res = null;
    if (is_array($cacheobject)) {
        $expireobj = intval($cacheobject['date']) + $expire;
        if ($expireobj > $cachedate) {
            $iscacheok = true;
        }
    }
    if ($iscacheok) {
        if (isset($cacheobject['output'])) {
            echo base64_decode($cacheobject['output']);
        }
        if (isset($cacheobject['scopeOut'])) {
            $res = $cacheobject['scopeOut'];
        }

        return $res;
    }
    ob_start();
    $res = lnxmcp()->runSequence($alt_sequence, alt_scopein);
    $output = ob_get_contents();
    ob_end_clean();
    $content = array(
        'scopeOut' => $res,
        'date' => $cachedate,
    );
    if (!empty($output)) {
        $content['output'] = base64_encode($output);
        echo $output;
    }
    lnxPutJsonFile($content, $name, $cachepath, 'json');

    return $res;
}

/**
 * lnxCacheFlush the cache
 * if use filter with a specific name content.
 *
 * @param mixed $filter
 */
function lnxCacheFlush($filter)
{
    $cachepath = lnxmcp()->getResource('path.cache');
    if (!is_dir($cachepath)) {
        return true;
    }
    try {
        foreach (scandir($cachepath) as $file) {
            $isremok = false;
            if (is_file($cachepath.DIRECTORY_SEPARATOR.$file)) {
                $isremok = true;
            }
            if (!empty($filter)) {
                if (strstr($file, $filter) == false) {
                    $isremok = false;
                }
            }
            if ($isremok) {
                unlink($cachepath.DIRECTORY_SEPARATOR.$file);
            }
        }

        return true;
    } catch (\Exception $e) {
        lnxmcp()->warning('lnxCacheFlush:'.$e->getMessage());

        return false;
    }
}
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
        DumpOnFatal("FATAL ERROR - lnxmcp is NOT SETTED!!! \n", true);
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
 * linhunixErrorHandlerDev.
 *
 * @param mixed $errno
 * @param mixed $errstr
 * @param mixed $errfile
 * @param mixed $errline
 */
function linhunixErrorHandlerDev($errno, $errstr, $errfile, $errline)
{
    lnxmcp()->info('linhunixErrorHandlerDev:'.$errno.'-'.$errstr);
    if (empty($errno)) {
        return false;
    }
    $errtype = $errno;
    $exit = false;
    $drvlvl = 0;
    switch ($errno) {
        case E_ERROR:
            lnxmcp()->error($errstr.'['.$errfile.'] ['.$errline.']');
            lnxmcp()->supportmail($errstr);
            $exit = true;
            break;
        case E_PARSE:
        case E_CORE_ERROR:
        case E_COMPILE_ERROR:
        case E_RECOVERABLE_ERROR:
        case E_USER_ERROR:
            lnxmcp()->error($errstr.'['.$errfile.'] ['.$errline.']');
            lnxmcp()->supportmail($errstr);
            break;
        case E_USER_DEPRECATED:
        case E_WARNING:
        case E_USER_WARNING:
            lnxmcp()->warning($errstr.'['.$errfile.'] ['.$errline.']');
            break;
        case E_NOTICE:
        case E_USER_NOTICE:
            $errtype = 'INF';
            lnxmcp()->info($errstr.'['.$errfile.'] ['.$errline.']');
            break;
        default:
            $errtype = 'DBG';
            lnxmcp()->debug($errstr.'['.$errfile.'] ['.$errline.']');
            break;
    }
    if ($exit) {
        \header('HTTP/1.1 302 Moved Temporarily', true, 302);
        \header('Location: /500', true, 500);
        exit(1);
        exit(1);
    }

    return true;
}
/**
 * linhunixFatalHandlerDev.
 */
function linhunixFatalHandlerDev()
{
    $errfile = 'unknown file';
    $errstr = 'shutdown';
    $errno = E_CORE_ERROR;
    $errline = 0;

    $error = error_get_last();

    if ($error !== null) {
        $errno = $error['type'];
        $errfile = $error['file'];
        $errline = $error['line'];
        $errstr = $error['message'];
    }
    linhunixErrorHandlerDev($errno, $errstr, $errfile, $errline);
}
/**
 * mcpErrorHandlerInit.
 */
function mcpErrorHandlerInit()
{
    $old_error_handler = set_error_handler('linhunixErrorHandlerDev');
    register_shutdown_function('linhunixFatalHandlerDev');
}

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
    $mcpCheckFile = lnxmcp()->getCfg('mcp.path').'/../mcp_modules/Chk/Shell/mcpCheck.php';
    lnxmcp()->info('Try to load CheckModule:'.$mcpCheckFile);
    if (file_exists($mcpCheckFile)) {
        echo "load Check Env on $mcpCheckFile..\n";
        include_once $mcpCheckFile;
        echo "Run mcpCheck:\n";
        LinHUniX\McpModules\Chk\Shell\mcpCheck($checkmenu);
        echo "Check Complete!!\n";
    }
}
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
function lnxmcpDbM($command = null, $element = null)
{
    if (empty($command)) {
        $command = 'help';
    }
    if (empty($element)) {
        $element = null;
    }
    $mcpCheckFile = lnxmcp()->getCfg('mcp.path').'/../mcp_modules/DbMig/Shell/mcpDbMigrate.php';
    lnxmcp()->info('Try to load DbMigrateModule:'.$mcpCheckFile);
    if (file_exists($mcpCheckFile)) {
        echo "load DbMigrate Env on $mcpCheckFile..\n";
        include_once $mcpCheckFile;
        echo "Run DbMigrate:$command\n";
        new LinHUniX\McpModules\DbMig\Shell\mcpDbMigrate($command, $element);
        echo "DbMigrate Complete!!\n";
    }
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
    $hfile = realpath($path);
    if ($hfile == '') {
        $hfile = $path;
    }
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
 * LnxMcpFullDebugOn.
 */
function LnxMcpFullDebugOn()
{
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(-1);
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
