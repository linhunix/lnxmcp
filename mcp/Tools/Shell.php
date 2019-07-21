<?php
/**
 * Created by PhpStorm.
 * User: linhunix
 * Date: 9/25/2018
 * Time: 2:55 PM.
 */
function mcpRunShell()
{
    global $app_path,$mcp_path,$argc,$argv,$cfg;
    lnxmcp()->setCfg('app.type', 'shell');
    if (file_exists(__DIR__.'/Shell_Header.txt')) {
        $content = file_get_contents(__DIR__.'/Shell_Header.txt');
        $content = str_replace('{{version}}', lnxMcpVersion(), $content);
        echo $content;
    }
    echo '';
    $help = array();
    $help['lnxmcp-mnu'] = "Run a Menu \n req arg: <menu name>";
    $help['lnxmcp-tag'] = "Run a Tag \n req arg: <tag name>";
    $help['lnxmcp-chk'] = "Run a Check\n  req arg: <check name>";
    $help['lnxmcp-adm'] = "Run a Admin\n  req arg: <check name>";
    $help['lnxmcp-cmd'] = "Run a command\n  req arg: jsonarr '{\"name\":\"value\"}' or name=value element";
    $help['lnxmcp-snd'] = "Run a sendmail\n  req arg: jsonarr '{\"name\":\"value\"}' or name=value element";
    $help['lnxmcp-dbm'] = "Run a db migrate \n  req arg: < command name> <element name>";
    $help['lnxmcp-phr'] = "Generate a phar file of the progam\n req arg <type |shell>";
    $scopein=array();
    $argtmp=$argv;
    $scopein["shell_src"]=@$argtmp[0];
    $scopein["shell_cmd"]=@$argtmp[1];
    $scopein["name"]=@$argtmp[2];
    $cmd=$argtmp[1];
    $name=$argtmp[2];
    unset($argtmp[0]);
    unset($argtmp[1]);
    foreach ($argtmp as $an=>$argctl){
        if (strstr($argctl,'=')!=false){
            $argcar=explode('=',$argctl);
            $scopein[$argcar[0]]=$argcar[1];
        } elseif (strstr($argctl,'{')!=false){
            $argcar=json_decode($argctl,1);
            if (is_array($argcar)){
                foreach ($argcar as $ak =>$av) {
                    $scopein[$ak]=$av;
                }
            }else{
                echo "issue on convert : [".json_last_error_msg()."]".$argctl.PHP_EOL;
            }
        }else{
            $scopein[$argctl]=true;
        }
    }
    echo "RUN $cmd ".PHP_EOL;
    if (isset($cmd)) {
        lnxmcp()->debugVar('head-shell', 'argv', $argv);
        switch ($cmd) {
            case 'lnxmcp-mnu':
                echo "Esecute mnu: $name ".PHP_EOL;
                lnxmcp()->runMenu($name,$scopein);
                break;
            case 'lnxmcp-tag':
                echo "Esecute Tag: $name ".PHP_EOL;
                lnxmcp()->runTag($name,$scopein);
                break;
            case 'lnxmcp-chk':
                echo "Esecute Check Sequence: $name ".PHP_EOL;
                lnxmcpChk($name);
                break;
            case 'lnxmcp-adm':
                echo "Esecute Administrator: $name ".PHP_EOL;
                lnxmcpAdm($name);
                break;
            case 'lnxmcp-dbm':
                echo "Esecute Database Migration: $name ".PHP_EOL;
                lnxmcpDbM($name, $argtmp[3]);
                break;
            case 'lnxmcp-cmd':
                echo "Esecute Command ".PHP_EOL;
                lnxMcpCmd($scopein,$argv);
                break;
            case 'lnxmcp-snd':
                echo "Esecute SendMail ".PHP_EOL;
                lnxmcp()->mail('sendmail', $scopein);
                break;
            case 'lnxmcp-dmp':
                // NOT PRESENT ON HELP FOR SECURITY QUESTION
                var_dump(lnxmcp());
                break;
            case 'lnxmcp-dcf':
                // NOT PRESENT ON HELP FOR SECURITY QUESTION
                var_export($cfg);
                break;
            case 'lnxmcp-cfg':
                // NOT PRESENT ON HELP FOR SECURITY QUESTION
                echo " the config is :\n";
                foreach (((array) $cfg) as $ik => $item) {
                    if (is_array($item)) {
                        echo "$ik is:\n";
                        foreach ($item as $cfgk => $cfgv) {
                            if (is_string($cfgv) || is_bool($cfgv) || is_int($cfgv)) {
                                echo '-->'.$cfgk.':'.var_export($cfgv, 1)."\n";
                            } else {
                                echo '-->'.$cfgk.": is set as object\n";
                            }
                        }
                    }
                }
                break;
            case 'lnxmcp-phr':
                if ($argv[2] == 'shell') {
                    LinHUniX\Mcp\Tools\pharizeShell::run();
                } else {
                    LinHUniX\Mcp\Tools\pharizeBase::run();
                }
                break;
            case 'help':
                echo "lnxmcp <action> <args.....>\n";
                if (file_exists($mcp_path.'/Help.txt')) {
                    echo file_get_contents($mcp_path.'/Help.txt');
                } else {
                    echo " the help is :\n\n";
                    foreach ($help as $hk => $hv) {
                        echo '[ '.$hk." ]:\n--- Desc:  ---\n".$hv."\n--- End Desc ---\n\n";
                    }
                }
                break;
            default:
                echo 'not valid argv';
                var_dump($argv);
        }
    }
}
