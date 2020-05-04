<?php

////////////////////////////////////////////////////////////////////////////////
// EXIT /CONFIG
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
 * lnxShutdown function
 * @return number
 */
function lnxShutdown() {
    $deadlst=lnxmcp()->getCfg('mcp.exitlist');
    if (is_array($deadlst)){
        foreach($deadlst as $dk=>$da){
            $dobj=lnxmcp()->getCfg($dk);
            if ($dobj==null){
                continue;
            }
            switch ($da){
                case 'close';
                    if (method_exists($dobj,'close')){
                        $dobj->close();
                    }
                    $dobj=null;
                break;
                case 'null';
                    $dobj=null;
                break;
                default:
                    $dobj=null;
                break;
            }
        }
    }
    gc_collect_cycles();
    gc_collect_cycles();
    return 0;
}
/**
 * mcpShutDownInit
 * @return void
 */
function mcpShutDownInit () {
    register_shutdown_function("lnxShutdown");
}