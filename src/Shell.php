<?php
/**
 * Created by PhpStorm.
 * User: freetimers
 * Date: 9/25/2018
 * Time: 2:55 PM
 */
function mcpRunShell()
{
    global $app_path,$mcp_path,$argc,$argv,$cfg;
    if (file_exists ($app_path . "/Header.txt")) {
        echo file_get_contents ($app_path . "/Header.txt");
    }
    echo "";
    if (isset($argv[1])) {
        lnxmcp ()->debugVar ("head-shell", "argv", $argv);
        switch ($argv[1]) {
            case "help":
                echo "lnxmcp <action> <args.....>\n";
                if (file_exists ($mcp_path . "/Help.txt")) {
                    echo file_get_contents ($mcp_path . "/Help.txt");
                }
                break;
            case "menu":
                lnxmcp ()->runMenu ($argv[2]);
                break;
            case "check":
                lnxmcp ()->runCheck ($argv[2]);
            case "lnxmcp-ctl":
                lnxmcp ()->controllerCommon ($argv[2], false, $argv, $argv[3]);
                break;
            case "lnxmcp-bcl":
                lnxmcp ()->showFullCommonBlock ($argv[2], $argv, $argv[3]);
                break;
            case "lnxmcp-dmp":
                var_dump (lnxmcp ());
                break;
            case "lnxmcp-pharize":
                if ($argv[2] == "shell") {
                    LinHUniX\Mcp\Tools\pharizeShell::run ();
                } else {
                    LinHUniX\Mcp\Tools\pharizeBase::run ();
                }
                break;
            default:
                echo "not valid argv";
                var_dump ($argv);
        }
    }
}