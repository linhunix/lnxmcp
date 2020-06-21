<?php
namespace LinHUniX\Chk\Model;

///////////////////////////////////////////////////////////////////////////////////////
///  INIT
///////////////////////////////////////////////////////////////////////////////////////
use LinHUniX\Mcp\masterControlProgram;
use LinHUniX\Mcp\Model\mcpBaseModelClass;

lnxmcp()->debug("Im In LinHUniX\McpModule\Chk..");
lnxmcp()->debug( "..Loading  mcpCheckModel Constant");

///////////////////////////////////////////////////////////////////////////////////////
///  CLASS  mcpCheckModel
///////////////////////////////////////////////////////////////////////////////////////
lnxmcp()->debug( "..Loading  mcpCheckModel Class");

class mcpCheckModelClass
{
    const mcpCheckModel_SpecialCheckLibs = "mcp_include_libs";
    const mcpCheckModel_SpecialCheckClass = "mcp_include_class";
    const mcpCheckModel_CheckFunction = "mcp_check_type";
    const mcpCheckModel_CheckName = "mcp_check_name";
    const mcpCheckModel_CheckArgCtl = "mcp_check_ctl";
    const mcpCheckModel_CheckArgIn = "mcp_check_in";
    const mcpCheckModel_CheckArgOut = "mcp_check_out";
    const mcpCheckModel_CheckOutput = "mcp_check_outdump";
    const mcpCheckModel_CheckNotOutput = "mcp_check_outnotdump";
    const mcpCheckModel_Out_type_array = "mcp_check_array";
    const mcpCheckModel_Out_type_isset = "mcp_check_isset";
    const mcpCheckModel_Out_type_class = "mcp_check_class";
    const mcpCheckModel_Out_type_func = "mcp_check_func";
    const mcpCheckModel_Out_type_model = "mcp_check_model";
    const mcpCheckModel_Out_type_false = "mcp_check_false";
    const mcpCheckModel_Out_type_true = "mcp_check_true";
    const mcpCheckModel_Out_type_number = "mcp_check_num"; 

    private $test;
    private $res;
    private $output;
    /**
     * 
     */
    function __construct()
    {
        lnxmcp()->debug( "mcpCheckModel is Initalized!!");
    }

    /**
     * 
     */
    public function RunTest(\LinHUniX\Mcp\masterControlProgram $mcp, array $testrequest)
    {
        lnxmcp()->debug("Inside Run Text..");
        $this->test = $testrequest;
        $this->checkArgBase();
        ob_start();
        switch ($this->test[self::mcpCheckModel_CheckFunction]) {
            case "cfg":
                $this->res = $mcp->getCfg();
                break;
            case "env":
                $this->res = getenv($this->test[self::mcpCheckModel_CheckName]);
                break;
            case "common":
                $this->res = $mcp->getCommon();
                break;
            case "command":
                $this->checkArgIn();
                $this->res = $mcp->runCommand($this->test[self::mcpCheckModel_CheckArgCtl], $this->test[self::mcpCheckModel_CheckArgIn]);
                break;
            case "sequence":
                $this->checkArgIn();
                $this->res = $mcp->runSequence($this->test[self::mcpCheckModel_CheckArgCtl], $this->test[self::mcpCheckModel_CheckArgIn]);
                break;
            case "menu":
                $this->checkArgIn();
                $this->res = $mcp->runMenu($this->test[self::mcpCheckModel_CheckName], $this->test[self::mcpCheckModel_CheckArgIn]);
                break;
            case "tag":
                $this->checkArgIn();
                $this->res = $mcp->runTag($this->test[self::mcpCheckModel_CheckName], $this->test[self::mcpCheckModel_CheckArgIn]);
                break;
        }
        $this->output=ob_get_clean();
        $this->checkArgOut();
        return true;
    }
    /**
     * 
     */
    public function checkArgIn()
    {
        lnxmcp()->debug( "check arg in ...:");
        if (!isset($this->test[self::mcpCheckModel_CheckArgIn])) {
            $this->DumpCheckAndExit(self::mcpCheckModel_CheckArgIn . " is Empty!");
        }
        if (!is_array($this->test[self::mcpCheckModel_CheckArgIn])) {
            $this->DumpCheckAndExit(self::mcpCheckModel_CheckArgIn . " is not Array!");
        }
        lnxmcp()->debug(".." . self::mcpCheckModel_CheckArgIn . " is " . print_r($this->test[self::mcpCheckModel_CheckArgIn], 1));
    }
    public function checkArgBase()
    {
        lnxmcp()->debug( "check arg base ...:");
        if (!isset($this->test[self::mcpCheckModel_CheckFunction])) {
            $this->DumpCheckAndExit(self::mcpCheckModel_CheckFunction . " is Empty!");
        }
        lnxmcp()->debug( ".." . self::mcpCheckModel_CheckFunction . " is " . $this->test[self::mcpCheckModel_CheckFunction]);
        if (!isset($this->test[self::mcpCheckModel_CheckArgCtl])) {
            $this->DumpCheckAndExit(self::mcpCheckModel_CheckArgCtl . " is Empty!");
        }
        if (!is_array($this->test[self::mcpCheckModel_CheckArgCtl])) {
            $this->DumpCheckAndExit(mcpCheckModel_CheckArgCtl . " is not Array!");
        }
        lnxmcp()->debug( ".." . self::mcpCheckModel_CheckArgCtl . " is " . print_r($this->test[self::mcpCheckModel_CheckArgCtl], 1));
        if (isset($this->test[self::mcpCheckModel_CheckOutput])){
            if (!is_array($this->test[self::mcpCheckModel_CheckOutput])) {
                $this->DumpCheckAndExit(self::mcpCheckModel_CheckOutput . " is not Array!");
            }
        }
        lnxmcp()->debug( ".." . self::mcpCheckModel_CheckOutput . " is " . print_r($this->test[self::mcpCheckModel_CheckOutput], 1));
        if (!isset($this->test[self::mcpCheckModel_CheckName])) {
            $this->DumpCheckAndExit(mcpCheckModel_CheckName . " is Empty!");
        }
        lnxmcp()->debug( ".." . self::mcpCheckModel_CheckName . " is " . $this->test[self::mcpCheckModel_CheckName]);
    }


    public function assetarg($arg, $type, $desc)
    {
        lnxmcp()->debug( "Check IsSet...[" . $desc . "]");
        lnxmcp()->debug( "verify is ".print_r($type,1)."...");
        if (isset($arg)) {
            if (is_array($type)) {
                lnxmcp()->debug( "OK");
                $this->searcharg($arg, $type, $desc);// $subdesc . "[" . $ck . "]");
            } else {
                switch ($type) {
                    case self::mcpCheckModel_Out_type_isset:
                        break;
                    case self::mcpCheckModel_Out_type_true:
                        if ($arg != true) {
                            $this->DumpCheckAndExit("Not True");
                        }
                        break;
                    case self::mcpCheckModel_Out_type_false:
                        if ($arg != false) {
                            $this->DumpCheckAndExit("Not false");
                        }
                        break;
                    case self::mcpCheckModel_Out_type_array:
                        if (!is_array($arg)) {
                            $this->DumpCheckAndExit("Not Array");
                        }
                        break;
                    case self::mcpCheckModel_Out_type_func:
                        if (!is_callable($arg)) {
                            $this->DumpCheckAndExit("Not Function");
                        }
                        break;
                    case self::mcpCheckModel_Out_type_class:
                        if (!is_object($arg)) {
                            $this->DumpCheckAndExit("Not Object");
                        }
                        break;
                        case self::mcpCheckModel_Out_type_model:
                        if (!($arg instanceof mcpBaseModelClass)) {
                            $this->DumpCheckAndExit("Not Model");
                        }
                        break;
                    case self::mcpCheckModel_Out_type_number:
                        if (!is_numeric($arg)) {
                            $this->DumpCheckAndExit("Not Array");
                        }
                        break;
                    default:
                        if ($arg != $type) {
                            $this->DumpCheckAndExit("Not Equal to " . print_r($type, 1));
                        }
                        break;
                }
                lnxmcp()->debug("OK");
            }
        } else {
            $this->DumpCheckAndExit("Not Set!!");
        }
    }
    public function searcharg($in, $chk, $subdesc = "")
    {
        foreach ($chk as $ck => $cva) {
            if ($ck != "end" && $ck != "") {
                $this->assetarg($in[$ck], $cva, $ck);
            }
        }
    }
    public function assertOutput($output,$search,$desc){
        lnxmcp()->debug( "Check IsSet...[" . $desc . "]");
        lnxmcp()->debug( "verify is ".print_r($search,1)."...");
        foreach ($search as $string){
            if (stristr($output,$string)==false){
                DumpCheckAndExit("Output do not have: " . $string);
            }
        }
    }
    public function assertNotOutput($output,$search,$desc){
        lnxmcp()->debug( "Check IsSet...[" . $desc . "]");
        lnxmcp()->debug( "verify is ".print_r($search,1)."...");
        foreach ($search as $string){
            if (stristr($output,$string)!=false){
                DumpCheckAndExit("Output do have: " . $string);
            }
        }
    }
    public function checkArgOut()
    {
        lnxmcp()->info( "check arg out ...:");
        if (!isset($this->test[self::mcpCheckModel_CheckArgOut])) {
            DumpCheckAndExit(self::mcpCheckModel_CheckArgOut . " is Empty!");
        }
        lnxmcp()->info( ".." . self::mcpCheckModel_CheckArgOut . " is " . print_r($this->test[self::mcpCheckModel_CheckArgOut], 1));
        lnxmcp()->info( "--------------------------- OUT START ------------------------------------");
        $this->assetarg($this->res, $this->test[self::mcpCheckModel_CheckArgOut], "Result");
        if (isset($this->test[self::mcpCheckModel_CheckOutput])){
            $this->assertOutput($this->output,$this->test[self::mcpCheckModel_CheckOutput],"Output");            
        }
        if (isset($this->test[self::mcpCheckModel_CheckNotOutput])){
            $this->assertNotOutput($this->output,$this->test[self::mcpCheckModel_CheckNotOutput],"Not Output");            
        }
        lnxmcp()->info( "--------------------------- OUT END --------------------------------------");
        return true;
    }
    protected function DumpCheckAndExit($message = "")
    {
        echo '#LNXMCPCHK# SUMMARY START'.PHP_EOL;
        echo '#LNXMCPCHK# TEST IS '.$chkmenu.PHP_EOL;
        echo '#LNXMCPCHK# GO FAILED'.PHP_EOL;
        echo "#LNXMCPCHK# ERROR IS:" . $message . PHP_EOL;
        echo '#LNXMCPCHK# SUMMARY END'.PHP_EOL;
        LnxMcpExit($message);
    }
}

