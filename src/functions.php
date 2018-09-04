<?php
////////////////////////////////////////////////////////////////////////////////
// ERROR/CONFIG
////////////////////////////////////////////////////////////////////////////////
function DumpAndExit($message = "")
{
    $GLOBALS["cfg"]["lnxmcp"]->debug("DumpAndExit:" . $message);
    foreach (debug_backtrace() as $row => $debug)
    {
        $GLOBALS["cfg"]["lnxmcp"]->debug(implode("|-|", $debug));
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
    echo $message;
    foreach (debug_backtrace() as $errarr)
    {
        error_log( "-> " . $errarr["file"] . " : " . $errarr["line"] . " <br>");
    }

    foreach (get_included_files() as $filename)
    {
        error_log ("Load: $filename");
    }
    error_log("FATAL ERROR - lnxmcp is NOT SETTED!!! ");
    error_log(debug_print_backtrace());
    if ($exit == true)
    {
        exit(1);
    }
}
function lnxmcp()
{
    if (isset($GLOBALS["cfg"]["lnxmcp"]))
    {
        return $GLOBALS["cfg"]["lnxmcp"];
    } else
    {
        DumpOnFatal("FATAL ERROR - lnxmcp is NOT SETTED!!! \n", true);
    }
}
function FreeTimersErrorHandlerDev($errno, $errstr, $errfile, $errline) {
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
            $GLOBALS["cfg"]["lnxmcp"]->error($errstr . "[" . $errfile . "] [" . $errline . "]");
            break;
        case E_USER_DEPRECATED:
        case E_WARNING:
        case E_USER_WARNING:
            $GLOBALS["cfg"]["lnxmcp"]->warning($errstr . "[" . $errfile . "] [" . $errline . "]");
            break;
        case E_NOTICE:
        case E_USER_NOTICE:
            $errtype = "INF";
            $GLOBALS["cfg"]["lnxmcp"]->info($errstr . "[" . $errfile . "] [" . $errline . "]");
            break;
        default:
            $errtype = "DBG";
            $GLOBALS["cfg"]["lnxmcp"]->debug($errstr . "[" . $errfile . "] [" . $errline . "]");
            break;
    }
    if ($exit) {
        \header("HTTP/1.1 302 Moved Temporarily", true, 302);
        \header('Location: /500', true, 500);
        exit(1);
    }
    return true;
}
$old_error_handler = set_error_handler("FreeTimersErrorHandlerDev");

