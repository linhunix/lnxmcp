<?php
/**
 * Created by PhpStorm.
 * User: linhunix
 * Date: 9/4/2018
 * Time: 10:11 AM
 */
namespace LinHUniX\Mcp\Model;

use LinHUniX\Mcp\masterControlProgram;

class mcpServiceModelClass extends mcpBaseModelClass
{

    /**
     * @var argCfg
     */
    protected $argCfg;

    /**
     * getSvcCfg
     *
     * @param  string $name
     *
     * @return mixed
     */
    public function getSvcCfg($name = null)
    {
        if (empty($name)) {
            return $this->argCfg;
        }
        if (isset($this->argCfg[$name])) {
            return $this->argCfg[$name];
        }
        return null;
    }
    /**
     * getDriver extent the capacity of the  base class to have a facility to get a driver class
     *
     * @param  string $name
     *
     * @return class
     */
    public function getDriver($name)
    {
        return $this->getMcp()->getResource("Driver." . $name);
    }
    /**
     * runCommonEvent
     *
     * @param  mixed $name
     *
     * @return void
     */
    public function runCommonEvent($name)
    {
        $argin = $this->getMcp()->getCommon();
        $argin["event_type"] = "common";
        $argin["event_name"] = $name;
        $res = $this->run($this->argCtl, $argin);
        if (isset($res["return"])) {
            $res = $res["return"];
        }
        $this->getMcp()->setCommon($name, $res);
    }
    /**
     * runCommonEvent
     *
     * @param  mixed $name
     *
     * @return array
     */
    public function runEvent($type, $name, $scopeIn)
    {
        $scopeIn["event_type"] = $type;
        $scopeIn["event_name"] = $name;
        return $this->run($this->argCtl, $scopeIn);
    }

    /**
     * In this service class is premanaged the module core as reflection calling
     * so  inf asking an event this call the specific method if is present the order 
     *  type method
     *  name method
     *  type_name method
     *
     * @return void
     */
    protected function moduleCore()
    {
        $method = "";
        if (!empty($this->argIn["event_type"])) {
            if (method_exists($this, $this->argIn["event_type"])) {
                $this->$this->argIn["event_type"]();
                $method .= $this->argIn["event_type"];
            }
        }
        if (!empty($scopeIn["event_name"])) {
            if (method_exists($this, $this->argIn["event_name"])) {
                $this->$this->argIn["event_name"]();
                if (!empty($method)) {
                    $method .= "_";
                }
                $method .= $this->argIn["event_name"];
            }
        }
        if (!empty($method)) {
            if (method_exists($this, $method)) {
                $this->$method();
            }
        }
    }

    /**
     * @param array (reference of) $scopeCtl => calling Controlling definitions
     * @param array (reference of) $scopeIn temproraney array auto cleanable
     */
    public function __construct(masterControlProgram &$mcp, array $scopeCtl, array $scopeIn)
    {
        parent::__construct($mcp, $scopeCtl, $scopeIn);
        $this->argCfg = $scopeCtl;
        $cfgfile = str_replace("\\", ".", __NAMESPACE__);
        $cfgarr = lnxGetJsonFile($cfgfile, $mcp->getCfg("app.cfg"), "json");
        if (is_array($cfgarr)) {
            foreach ($cfgarr as $k => $v) {
                $this->argCfg[$k] = $v;
            }
        }
    }
}
