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

/**
 *  LnxMcpDebug use debugvar function
 *  as a function.
 *
 *  @param string $where your are ;
 *  @param string $var name or label;
 *  @param mixed $value;
 */
function lnxMcpDebug($where, $var, $value)
{
    lnxmcp()->debugvar($where, $var, $value);
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
    if (lnxmcp()->isDebug()) {
        lnxmcp()->info('linhunixErrorHandlerDev:'.$errno.'-'.$errstr);
    }
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
    $errfile = 'no file';
    $errstr = 'End Of Service';
    $errno = 0;
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
 * LnxMcpFullDebugOn.
 */
function LnxMcpFullDebugOn()
{
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(-1);
}
