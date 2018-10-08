<?php

namespace LinHUniX\McpModules\Chk;

///////////////////////////////////////////////////////////////////////////////////////
///  INIT
///////////////////////////////////////////////////////////////////////////////////////
use LinHUniX\Mcp\masterControlProgram;

echo "Im In LinHUniX\McpModule\Chk.. \n";
echo "..Loading  mcpCheckModel Constant\n";
const mcpCheckModel_SpecialCheckLibs = "mcp_include_libs";
const mcpCheckModel_SpecialCheckClass = "mcp_include_class";
const mcpCheckModel_CheckFunction = "mcp_check_type";
const mcpCheckModel_CheckModule = "mcp_check_module";
const mcpCheckModel_CheckName = "mcp_check_name";
const mcpCheckModel_CheckArgIn = "mcp_check_in";
const mcpCheckModel_CheckArgOut = "mcp_check_out";
const mcpCheckModel_Out_type_array = "mcp_check_array";
const mcpCheckModel_Out_type_isset = "mcp_check_isset";
const mcpCheckModel_Out_type_false = "mcp_check_false";
const mcpCheckModel_Out_type_true = "mcp_check_true";
///////////////////////////////////////////////////////////////////////////////////////
///  CLASS  mcpCheckModel
///////////////////////////////////////////////////////////////////////////////////////
echo "..Loading  mcpCheckModel Class\n";

class mcpCheckModel
{
    private $test;
    private $res;

    function __construct ()
    {
        echo "mcpCheckModel is Initalized!!\n";
    }

    public function checkArgIn ()
    {
        echo "check arg in ...:\n";
        if (!isset($this->test[mcpCheckModel_CheckArgIn])) {
            DumpCheckAndExit (mcpCheckModel_CheckArgIn . " is Empty!");
        }
        echo ".." . mcpCheckModel_CheckArgIn . " is " . print_r ($this->test[mcpCheckModel_CheckArgIn], 1) . "\n";
    }

    public function RunTest (\LinHUniX\Mcp\masterControlProgram $mcp, array $testrequest)
    {
        echo "Inside Run Text..\n";
        $this->test = $testrequest;
        $this->checkArgBase ();
        switch ($this->test[mcpCheckModel_CheckFunction]) {
            case "cfg":
                $this->res = $mcp->getCfg ();
        }
        $this->checkArgOut ();
        return true;
    }

    public function checkArgBase ()
    {
        echo "check arg base ...:\n";
        if (!isset($this->test[mcpCheckModel_CheckFunction])) {
            DumpCheckAndExit (mcpCheckModel_CheckFunction . " is Empty!");
        }
        echo ".." . mcpCheckModel_CheckFunction . " is " . $this->test[mcpCheckModel_CheckFunction] . "\n";
        if (!isset($this->test[mcpCheckModel_CheckModule])) {
            DumpCheckAndExit (mcpCheckModel_CheckModule . " is Empty!");
        }
        echo ".." . mcpCheckModel_CheckModule . " is " . $this->test[mcpCheckModel_CheckModule] . "\n";
        if (!isset($this->test[mcpCheckModel_CheckName])) {
            DumpCheckAndExit (mcpCheckModel_CheckName . " is Empty!");
        }
        echo ".." . mcpCheckModel_CheckName . " is " . $this->test[mcpCheckModel_CheckName] . "\n";
    }

    public function checkArgOut ()
    {
        echo "check arg out ...:\n";
        if (!isset($this->test[mcpCheckModel_CheckArgOut])) {
            DumpCheckAndExit (mcpCheckModel_CheckArgOut . " is Empty!");
        }
        echo ".." . mcpCheckModel_CheckArgOut . " is " . print_r ($this->test[mcpCheckModel_CheckArgOut], 1) . "\n";
        $this->searcharg ($this->res, $this->test[mcpCheckModel_CheckArgOut], "Result");
        return true;
    }

    public function searcharg ($in, $chk, $subdesc = "")
    {
        echo "Check $subdesc....";
        foreach ($chk as $ck => $cva) {
            if (isset ($in[$ck])) {
                echo "IsSet...[" . $in[$ck] . "]";
                echo "verify is $cva..";
                if (is_array ($cva)) {
                    echo "OK\n";
                    $this->searcharg ($in[$ck], $cva, $subdesc . "[" . $ck . "]");
                } else {
                    switch ($cva) {
                        case mcpCheckModel_Out_type_isset:
                            break;
                        case mcpCheckModel_Out_type_true:
                            if ($in[$ck] != true) {
                                DumpCheckAndExit ("Not True");
                            }
                            break;
                        case mcpCheckModel_Out_type_false:
                            if ($in[$ck] != false) {
                                DumpCheckAndExit ("Not false");
                            }
                            break;
                        case mcpCheckModel_Out_type_array:
                            if (!is_array ($in[$ck])) {
                                DumpCheckAndExit ("Not Array");
                            }
                            break;
                        default:
                            if ($in[$ck] != $cva) {
                                DumpCheckAndExit ("Not Equal to " . print_r ($cva, 1));
                            }
                    }
                    echo "OK\n;";
                }
            }
        }
    }
}

///////////////////////////////////////////////////////////////////////////////////////
///  FUNCTION DumpCheckAndExit
///////////////////////////////////////////////////////////////////////////////////////
echo "..Loading  DumpCheckAndExit Function\n";
function DumpCheckAndExit ($message = "")
{
    echo "ERROR!!:" . $message . "\n";
    DumpAndExit ($message);
}

///////////////////////////////////////////////////////////////////////////////////////
///  FUNCTION mcpCheck
///////////////////////////////////////////////////////////////////////////////////////
echo "..Loading  mcpCheck Function\n";
function mcpCheck ()
{
    echo "Function mcpCheck....\n";
    global $argv;
    $chkarg = array ();
    $chkcls = null;
    echo ".. Verify the Mcp Init :\n";
    if (lnxmcp () instanceof masterControlProgram) {
        echo "Mcp is ready\n";
    } else {
        DumpCheckAndExit ("Mcp is Not READY!!!!");
    }
    echo "... Legacy Cfg\n";
    if (!isset($GLOBALS["cfg"])) {
        DumpCheckAndExit ("Global cfg is Not READY!!!!");
    }
    echo "... Legacy lnxmcp\n";
    if (!isset($GLOBALS["mcp"])) {
        DumpCheckAndExit ("Global lnxmcp is Not READY!!!!");
    }
    echo "OK\n";
    echo ".. Verify internal api\n";
    if (!isset($GLOBALS["cfg"]["Logger"])) {
        print_r ($GLOBALS["cfg"]);
        DumpCheckAndExit ("Logger Provider is Not READY!!!!");
    }
    if (!isset($GLOBALS["cfg"]["app.ver"])) {
        DumpCheckAndExit ("Setting Provider is Not READY!!!!");
    }
    echo "... SetDebug true\n";
    if ($GLOBALS["mcp"]->setCfg ("app.debug", "true") != true) {
        DumpCheckAndExit ("Setting Debug is Not READY!!!!");
    }
    if (lnxmcp ()->getCfg ("app.debug") != "true") {
        DumpCheckAndExit ("Getting Debug is Not READY!!!!");
    }
    lnxmcp ()->debug ("test debug message");
    lnxmcp ()->info ("test info message");
    lnxmcp ()->warning ("test warning message");
    lnxmcp ()->error ("test error message");
    echo "OK\n";
    echo ".. Check File Arg\n";
    if (isset($argv[2])) {
        $chkfile = lnxmcp ()->getResource ("path") . "/mcp_test/" . $argv[2] . ".json";
        echo ".. Check File json $chkfile \n";
        if (file_exists ($chkfile)) {
            $chkarg = json_decode (file_get_contents ($chkfile), 1);
        } else {
            DumpCheckAndExit ("File Not Found " . $chkfile);
        }
    } else {
        DumpCheckAndExit ("No Test Specified!");
    }
    if (!is_array ($chkarg)) {
        DumpCheckAndExit ("Args Is not an Array!!!");
    }
    echo "Arg is :";
    print_r ($chkarg);
    echo ".. Check Php Libs\n";
    if (isset($chkarg[mcpCheckModel_SpecialCheckLibs])) {
        if (!is_array ($chkarg[mcpCheckModel_SpecialCheckLibs])) {
            $ar = array (
                $chkarg[mcpCheckModel_SpecialCheckLibs]
            );
            $chkarg[mcpCheckModel_SpecialCheckLibs] = $ar;
        }
        foreach ($chkarg[mcpCheckModel_SpecialCheckLibs] as $libload) {
            $incres = lnxmcp ()->getResource ("path") . "/" . $libload;
            echo "... Try to load $incres \n";
            if (file_exists ($incres)) {
                include_once $incres;
            } else {
                DumpCheckAndExit ($incres . " not Fuund");
            }
        }
    }
    echo ".. Check Class \n";
    if (isset($chkarg[mcpCheckModel_SpecialCheckClass])) {
        echo "... Try to Call " . $chkarg[mcpCheckModel_SpecialCheckClass] . " \n";
        if (class_exists ($chkarg[mcpCheckModel_SpecialCheckClass])) {
            $chkcls = new $chkarg[mcpCheckModel_SpecialCheckClass]();
        } else {
            DumpCheckAndExit ($chkarg[mcpCheckModel_SpecialCheckClass] . " not Fuund");
        }
    } else {
        $chkcls = new mcpCheckModel();
    }
    echo ".. Verify Class is mcpCheckModel \n";
    if ($chkcls instanceof mcpCheckModel) {
        if ($chkcls->RunTest (lnxmcp (), $chkarg)) {
            echo "TEST SUCCESS!!!\n";
        } else {
            DumpCheckAndExit ("TEST FAILED!!");
        }
    } else {
        DumpCheckAndExit ("BAD CLASS is not mcpCheckModel");
    }
}

echo "Check Env Loaded!!\n";
