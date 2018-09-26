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
                /**
                 * Run Module as Check sequence
                 * @param string $cfgvalue name of the Doctrine
                 * @param string $modinit  Module name where is present the code and be load and initalized
                 * @param string $path     path where present the basedirectory of the data
                 * @param array $scopeIn   Input Array with the value need to work
                 * @param string $subcall  used if the name of the functionality ($callname) and the subcall are different
                 * @return array $ScopeOut
                 */
                $mcpCheckFile = $app_path . "/mcp_modules/Chk/mcpCheck.php";
                if (file_exists ($mcpCheckFile)) {
                    include_once ($mcpCheckFile);
                    mcpCheckFile ();
                }
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