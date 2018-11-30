<?php
////////////////////////////////////////////////////////////////////////////////
// ERROR/CONFIG
////////////////////////////////////////////////////////////////////////////////
/**
 * Exit with closure procedure
 *
 * @param  mixed $message
 *
 * @return void
 */
function LnxMcpExit($message = "")
{
    lnxmcp()->info("DumpAndExit:" . $message);
    lnxmcp()->runTag("Exit");
    exit();
}
if (function_exists("DumpAndExit") != true) {
    /**
     * DumpAndExit
     *
     * @param  mixed $message
     *
     * @return void
     */
    function DumpAndExit($message = "")
    {
        lnxmcp()->info("DumpAndExit:" . $message);
        lnxmcp()->runTag("Exit");
        foreach (debug_backtrace() as $row => $debug) {
            if (is_array($debug)) {
                foreach ($debug as $drow => $ddebug) {
                    lnxmcp()->debug("[" . $drow . "]>>" . print_r($ddebug, 1));
                }
            }
        }
        exit();
    }
    /**
     * this version has only the error log call because is work when is present a big issue
     * @param String $message
     * @param bool $exit
     */
    function DumpOnFatal($message, $exit = false)
    {
        lnxmcp()->runTag("Fatal");
        lnxmcp()->runTag("Exit");
        echo $message;
        foreach (debug_backtrace() as $errarr) {
            error_log("-> " . $errarr["file"] . " : " . $errarr["line"] . " <br>");
        }
        foreach (get_included_files() as $filename) {
            error_log("Load: $filename");
        }
        error_log("FATAL ERROR - lnxmcp is NOT SETTED!!! ");
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
        $classPath = $folder . DIRECTORY_SEPARATOR . $className . '.php';
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
        if (is_dir($folder = $srcPath . DIRECTORY_SEPARATOR . $item)) {
            $autoLoadFolders[] = $folder;
        }
    }
    spl_autoload_register('legacyAutoload', true/*, true*/ );
}

/**
 * linhunix json array converter 
 *
 * @param  mixed $file
 * @param  mixed $path if is need 
 * @param  mixed $ext with out the '.'
 *
 * @return any json object converted 
 */
function lnxGetJsonFile($file, $path = "", $ext = "")
{
    $jfile = $path;
    if ($jfile != "") {
        $jfile .= DIRECTORY_SEPARATOR . $file;
    }
    if ($ext != "") {
        $jfile .= "." . $ext;
    }
    if (file_exists($jfile)) {
        try {
            lnxmcp()->info("lnxGetJsonFile:" . $jfile);
            return json_decode(file_get_contents($jfile), true);
        } catch (\Exception $e) {
            lnxmcp()->warning("lnxGetJsonFile>>file:" . $jfile . " and err:" . $e->get_message());
            return false;
        }
    } else {
        lnxmcp()->info("lnxGetJsonFile>>file:" . $jfile . " and not found");
    }
    return null;
}

/**
 * lnxmcp
 *
 * @return mastercontrolprogram
 */
function lnxmcp()
{
    if (isset($GLOBALS["mcp"])) {
        return $GLOBALS["mcp"];
    } else {
        DumpOnFatal("FATAL ERROR - lnxmcp is NOT SETTED!!! \n", true);
    }
}

/**
 * LinHUnix Master Control Program 
 * Fast Tag caller 
 *
 * @param  mixed $tagname
 * @param  mixed $scopein
 *
 * @return void
 */
function lnxMcpTag($tagname, array $scopein = array())
{
    lnxmcp()->runTag($tagname, $scopeIn);
}
/**
 * linhunixErrorHandlerDev
 *
 * @param  mixed $errno
 * @param  mixed $errstr
 * @param  mixed $errfile
 * @param  mixed $errline
 *
 * @return void
 */
function linhunixErrorHandlerDev($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        return false;
    }
    $errtype = $errno;
    $exit = false;
    $drvlvl = 0;
    switch ($errno) {
        case E_ERROR:
            $exit = true;
        case E_USER_ERROR:
            lnxmcp()->error($errstr . "[" . $errfile . "] [" . $errline . "]");
            break;
        case E_USER_DEPRECATED:
        case E_WARNING:
        case E_USER_WARNING:
            lnxmcp()->warning($errstr . "[" . $errfile . "] [" . $errline . "]");
            break;
        case E_NOTICE:
        case E_USER_NOTICE:
            $errtype = "INF";
            lnxmcp()->info($errstr . "[" . $errfile . "] [" . $errline . "]");
            break;
        default:
            $errtype = "DBG";
            lnxmcp()->debug($errstr . "[" . $errfile . "] [" . $errline . "]");
            break;
    }
    if ($exit) {
        \header("HTTP/1.1 302 Moved Temporarily", true, 302);
        \header('Location: /500', true, 500);
        exit(1);
        exit(1);
    }
    return true;
}

/**
 * mcpErrorHandlerInit
 *
 * @return void
 */
function mcpErrorHandlerInit()
{
    $old_error_handler = set_error_handler("linhunixErrorHandlerDev");
}

