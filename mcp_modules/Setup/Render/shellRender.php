<?php
$scpctl=array(
    "type"=>"serviceCommonReturn",
    "module"=>"Setup",
    "vendor"=>"LinHUniX",
    "name"=>"setup"
);
if (!isset($scopeIn['action'])){
    $scopeIn['action']='help';
}
if ($scopeIn['action']==''){
    $scopeIn['action']='help';
}
$scopeIn["T"]="setup";
$scopeIn['E']=$scopeIn['action'];
echo "-------------------------------------------------------------------------------------\n";
echo " Setup Module\n";
echo "-------------------------------------------------------------------------------------\n";
echo " Action ".$scopeIn['action']."\n";
echo "-------------------------------------------------------------------------------------\n";
echo " Input ".print_r($scopeIn,1)."\n";
echo "-------------------------------------------------------------------------------------\n";
switch($scopeIn['action']){
    case 'cfgupd':
        echo "Setting cfg upgrade\n";
        lnxmcp()->RunCommand($scpctl,$scopeIn);
    break;
    case 'list':
        echo "show list of installation \n";
        $res=lnxmcp()->RunCommand($scpctl,$scopeIn);
        if(is_array($res['setup_list'])){
            ksort($res['setup_list']);
            foreach($res['setup_list'] as $lk=>$lv){
                // if ($lv!='Todo'){
                //     echo $lv;
                //     $lv=gmdate('d/m/Y H:m:s',intval($lv));
                // }
                echo "$lk: $lv\n";
            }
        }
    break;
    case 'logs':
        echo "show logs of installation \n";
        $res=lnxmcp()->RunCommand($scpctl,$scopeIn);
        if(is_array($res['setup_logs'])){
            ksort($res['setup_logs']);
            foreach($res['setup_logs'] as $lk=>$lv){
                $lk=gmdate('d/m/Y H:m:s',intval($lk));
                echo "$lk: $lv\n";
            }
        }
    break;
    case 'install':
        echo "try to install\n";
        $res=lnxmcp()->RunCommand($scpctl,$scopeIn);
        $msg='Install Error!!!';
        if (isset($res['setup_install'])){
            if ($res['setup_install']==true){
                $msg='Install Success';
            }
        }
        echo "$msg\n";
       $msg=lnxmcpSetup('message',array());
        echo $msg['setup_message']."\n";
     break;
    case 'remove':
        echo "try to remove\n";
        $res=lnxmcp()->RunCommand($scpctl,$scopeIn);
        $msg='Remove Error!!!';
        if (isset($res['setup_remove'])){
            if ($res['setup_remove']==true){
                $msg='Remove Success';
            }
        }
        echo "$msg\n";
        $msg=lnxmcpSetup('message',array());
        echo $msg['setup_message']."\n";
    break;
    case 'check':
        echo "try to check\n";
        $res=lnxmcp()->RunCommand($scpctl,$scopeIn);
        $msg='Check Error!!!';
        if (isset($res['setup_check'])){
            if ($res['setup_check']==true){
                $msg='Check Success';
            }
        }
        echo "$msg\n";
        $msg=lnxmcpSetup('message',array());
        echo $msg['setup_message']."\n";
     break;
    case 'batch':
        echo "try to batch\n";
        $res=lnxmcp()->RunCommand($scpctl,$scopeIn);
        $msg='batch Error!!!';
        if (isset($res['setup_batch'])){
            if ($res['setup_batch']==true){
                $msg='batch Success';
            }
        }
        echo "$msg\n";
        $msg=lnxmcpSetup('message',array());
        echo $msg['setup_message']."\n";
     break;
    case 'help':
        echo "cfgupd : add or remove a value (only string) from setup config.\n";
        echo "list   : show the list of the actual feature to install or installed.\n";
        echo "logs   : show the logs of the actual activites.\n";
        echo "install: install a specific features.\n";
        echo "remove : remove a specific feaurtes.\n";
        echo "check  : check a specific feaurtes.\n";
        echo "batch  : run a batch for a specific feaurtes.\n";
        echo "help   : Show this help.\n";
}
echo "\n\n";
echo "-------------------------------------------------------------------------------------\n";

