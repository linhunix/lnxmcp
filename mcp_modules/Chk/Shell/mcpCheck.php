<?php
namespace LinHUniX\Chk\Shell;
lnxmcpUse('LinHUniX\Chk\Model\mcpCheckModelClass');
use LinHUniX\Chk\Model\mcpCheckModelClass;
use LinHUniX\Mcp\masterControlProgram;

///////////////////////////////////////////////////////////////////////////////////////
///  FUNCTION DumpCheckAndExit
///////////////////////////////////////////////////////////////////////////////////////
lnxmcp()->debug( "..Loading  DumpCheckAndExit Function");
function DumpCheckAndExit($message = "")
{
    echo '#LNXMCPCHK# SUMMARY START'.PHP_EOL;
    echo '#LNXMCPCHK# TEST IS '.$chkmenu.PHP_EOL;
    echo '#LNXMCPCHK# GO FAILED'.PHP_EOL;
    echo "#LNXMCPCHK# ERROR IS:" . $message . PHP_EOL;
    echo '#LNXMCPCHK# SUMMARY END'.PHP_EOL;
    LnxMcpExit($message);
}

///////////////////////////////////////////////////////////////////////////////////////
///  FUNCTION mcpCheck
///////////////////////////////////////////////////////////////////////////////////////
lnxmcp()->debug("..Loading  mcpCheck Function");
function mcpCheck($chkmenu = null)
{
    lnxmcp()->debug( "Function mcpCheck....");
    if ($chkmenu == null) {
        global $argv;
        $chkmenu = $argv[2];
    }
    $chkarg = array();
    $chkcls = null;
    lnxmcp()->debug( ".. Verify the Mcp Init :");
    if (lnxmcp() instanceof masterControlProgram) {
        lnxmcp()->debug( "Mcp is ready");
    } else {
        DumpCheckAndExit("Mcp is Not READY!!!!");
    }
    lnxmcp()->debug( "... Legacy Cfg");
    if (!isset($GLOBALS["cfg"])) {
        DumpCheckAndExit("Global cfg is Not READY!!!!");
    }
    lnxmcp()->debug( "... Legacy lnxmcp");
    if (!isset($GLOBALS["mcp"])) {
        DumpCheckAndExit("Global lnxmcp is Not READY!!!!");
    }
    lnxmcp()->debug( "OK");
    lnxmcp()->debug( ".. Verify internal api");
    if (!isset($GLOBALS["cfg"]["Logger"])) {
        lnxmcp()->debug(print_r($GLOBALS["cfg"],1));
        DumpCheckAndExit("Logger Provider is Not READY!!!!");
    }
    if (!isset($GLOBALS["cfg"]["app.ver"])) {
        DumpCheckAndExit("Setting Provider is Not READY!!!!");
    }
    lnxmcp()->debug( "... SetDebug true");
    if ($GLOBALS["mcp"]->setCfg("app.debug", "true") != true) {
        DumpCheckAndExit("Setting Debug is Not READY!!!!");
    }
    if (lnxmcp()->getCfg("app.debug") != "true") {
        DumpCheckAndExit("Getting Debug is Not READY!!!!");
    }
    lnxmcp()->debug("test debug message");
    lnxmcp()->info("test info message");
    lnxmcp()->warning("test warning message");
    lnxmcp()->error("test error message");
    lnxmcp()->debug( "OK");
    ////////////////////////////////////////////////////////////////////////////////////////////////
    lnxmcp()->debug( "CHECK SYSTEM");
    ////////////////////////////////////////////////////////////////////////////////////////////////
    $apppath = lnxmcp()->getCfg('app.path');
    $cfgpath = lnxmcp()->getCfg('app.path.config');
    $mcpath = lnxmcp()->getCfg('mcp.path');
    $admpath =lnxmcp()->getCfg('app.mod.path.LinHUniX.LnxMcpAdm');
    $admpathS =lnxmcp()->getCfg('app.mod.path.LinHUniX.LnxMcpAdmShell');
    $admpathH =lnxmcp()->getCfg('app.mod.path.LinHUniX.LnxMcpAdmHttpd');
    $bincmd = PHP_BINARY;
    $setpath = $cfgpath.'/mcp.settings.json';
    $idxpath = $apppath.'/index.php';
    $id2path = $apppath.'/app.php';
    $cfgok = 'KO';
    $appok = 'KO';
    $idxok = 'KO';
    $setok = 'KO';
    if (is_dir($apppath)) {
        $appok = 'OK';
    }
    if ($apppath == '//') {
        $appok = 'KO';
    }
    if (is_dir($cfgpath)) {
        $cfgok = 'OK';
    }
    if (file_exists($setpath)) {
        $setok = 'OK';
    }
    if (file_exists($idxpath)) {
        $idxok = 'OK';
    } elseif (file_exists($id2path)) {
        $idxok = 'OK';
        $idxpath = $id2path;
    }
    $scopeCmdIn=array(
        'mode'=>$defmod,
        'cmd.php'=>$bincmd,
        'mcp.path'=>$mcpath,
        'adm.path'=>$admpath,
        'adm.path.shell'=>$admpathS,
        'adm.path.httpd'=>$admpathH,
        'app.ok'=>$appok,
        'app.path'=>$apppath,
        'cfg.ok'=>$cfgok,
        'cfg.path'=>$cfgpath,
        'idx.ok'=>$idxok,
        'idx.path'=>$idxpath,
        'set.ok'=>$setok,
        'set.path'=>$setpath
    );
    lnxmcp()->info('//////////////////////////////////////////////////////////' );
    lnxmcp()->info('|-> PATH APP:'.$scopeCmdIn['app.path'].'('.$scopeCmdIn['app.ok'].')' );
    lnxmcp()->info('|-> PATH IDX:'.$scopeCmdIn['idx.path'].'('.$scopeCmdIn['idx.ok'].')' );
    lnxmcp()->info('|-> PATH CFG:'.$scopeCmdIn['cfg.path'].'('.$scopeCmdIn['cfg.ok'].')' );
    lnxmcp()->info('|-> PATH STS:'.$scopeCmdIn['set.path'].'('.$scopeCmdIn['set.ok'].')' );
    lnxmcp()->info('|-> PATH MCP:'.$scopeCmdIn['mcp.path'] );
    lnxmcp()->info('|-> PATH ADM:'.$scopeCmdIn['adm.path'] );
    lnxmcp()->info('|-> PATH ADS:'.$scopeCmdIn['adm.path.shell'] );
    lnxmcp()->info('|-> PATH ADH:'.$scopeCmdIn['adm.path.httpd'] );
    lnxmcp()->info('|-> PATH PHP:'.$scopeCmdIn['cmd.php'] );
    lnxmcp()->info('|-> VERS PHP:'.PHP_VERSION );
    lnxmcp()->info('|-> VERS SYS:'.PHP_OS );
    lnxmcp()->info('|-> NAME SYS:'.$_SERVER['HOSTNAME'] );
    lnxmcp()->info('//////////////////////////////////////////////////////////' );
    foreach(lnxmcp()->getCfg() as $ck=>$cv){
        if (stristr($ck,'app.mod.path')!=false){
            lnxmcp()->info('|-> '.$ck.':'.$cv );
        }
    }
    lnxmcp()->info('//////////////////////////////////////////////////////////' );
    ////////////////////////////////////////////////////////////////////////////////////////////////

    lnxmcp()->debug( ".. Check File Arg");
    if ($chkmenu != null) {
        if (lnxmcp()->getCommon('chk:'.$chkmenu)!=null){
            $chkarg=lnxmcp()->getCommon('chk:'.$chkmenu);
            $chkfile='chk:'.$chkmenu;
        }else{
            $chkfile = lnxmcp()->getResource("path") . "/mcp_test/" . $chkmenu . ".json";
            lnxmcp()->debug( ".. Check File json $chkfile ");
            if (file_exists($chkfile)) {
                $chkarg = json_decode(file_get_contents($chkfile), 1);
            } else {
                DumpCheckAndExit("File Not Found " . $chkfile);
            }
        }
    } else {
        DumpCheckAndExit("No Test Specified!");
    }
    if (!is_array($chkarg)) {
        DumpCheckAndExit("Args Is not an Array!!!");
    }
    lnxmcp()->debug( "Arg is :");
    lnxmcp()->debug(print_r($chkarg,1));
    lnxmcp()->debug( ".. Check Php Libs");
    if (isset($chkarg[mcpCheckModelClass::mcpCheckModel_SpecialCheckLibs])) {
        if (!is_array($chkarg[mcpCheckModelClass::mcpCheckModel_SpecialCheckLibs])) {
            $ar = array(
                $chkarg[mcpCheckModelClass::mcpCheckModel_SpecialCheckLibs]
            );
            $chkarg[mcpCheckModelClass::mcpCheckModel_SpecialCheckLibs] = $ar;
        }
        foreach ($chkarg[mcpCheckModelClass::mcpCheckModel_SpecialCheckLibs] as $libload) {
            $incres = lnxmcp()->getResource("path") . "/" . $libload;
            lnxmcp()->debug( "... Try to load $incres ");
            if (file_exists($incres)) {
                include_once $incres;
            } else {
                DumpCheckAndExit($incres . " not Fuund");
            }
        }
    }
    lnxmcp()->debug( ".. Check Class ");
    if (isset($chkarg[mcpCheckModelClass::mcpCheckModel_SpecialCheckClass])) {
        lnxmcp()->debug( "... Try to Call " . $chkarg[mcpCheckModelClass::mcpCheckModel_SpecialCheckClass] );
        if (class_exists($chkarg[mcpCheckModelClass::mcpCheckModel_SpecialCheckClass])) {
            $chkcls = new $chkarg[mcpCheckModelClass::mcpCheckModel_SpecialCheckClass]();
        } else {
            DumpCheckAndExit($chkarg[mcpCheckModelClass::mcpCheckModel_SpecialCheckClass] . " not Fuund");
        }
    } else {
        $chkcls = new mcpCheckModelClass();
    }
    lnxmcp()->debug( ".. Verify Class is mcpCheckModel ");
    $resmsg="";
    $reschk=false;
    if ($chkcls instanceof mcpCheckModelClass) {
        if ($chkcls->RunTest(lnxmcp(), $chkarg)) {
            $resmsg="TEST GO SUCCESS";
            $recchk=true;
        } else {
            $resmsg="TEST GO FAILED";
        }
    } else {
        $resmsg="BAD CLASS is not mcpCheckModelClass";
    }
    echo '#LNXMCPCHK# SUMMARY START'.PHP_EOL;
    echo '#LNXMCPCHK# TEST IS '.$chkmenu.PHP_EOL;
    echo '#LNXMCPCHK# '.$resmsg.PHP_EOL;
    echo '#LNXMCPCHK# SUMMARY END'.PHP_EOL;
    lnxmcp()->mail(null,array(
        "to"=>"lnxmcp@linhunix.com",
        "from"=>"test@localhost",
        "subject"=>"lnxmcp - Run Text",
        "message"=>"Arg<hr>\n<pre>".print_r($chkarg,1)."</pre><hr>\n".$resmsg
    ));
    lnxmcp()->debug( "Check Env Loaded!!");
    return $recchk;
}
