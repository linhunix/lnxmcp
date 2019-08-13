<?php

////////////////////////////////////////////////////////////////////////////////
// Application solution
////////////////////////////////////////////////////////////////////////////////
    if (file_exists($app_path.DIRECTORY_SEPARATOR.'main.php')) {
        include $app_path.DIRECTORY_SEPARATOR.'main.php';
        DumpAndExit('End Of App');
    } elseif (isset($_SERVER['REQUEST_URI'])) {
        include_once __DIR__.'/Step/Http.step.php';
        new mcpRunHttp();
    } elseif (isset($_REQUEST['Menu'])) {
        lnxmcp()->runMenu($_REQUEST['Menu']);
    } else {
        $GLOBALS['mcp_preload'] .= ob_get_clean();
        include_once __DIR__.'/Step/Shell.step.php';
        mcpRunShell();
    }
