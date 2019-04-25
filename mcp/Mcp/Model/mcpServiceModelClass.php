<?php
/**
 * Created by PhpStorm.
 * User: linhunix
 * Date: 9/4/2018
 * Time: 10:11 AM.
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
     * getSvcCfg.
     *
     * @param string $name
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
     * getDriver extent the capacity of the  base class to have a facility to get a driver class.
     *
     * @param string $name
     *
     * @return class
     */
    public function getDriver($name)
    {
        return $this->getMcp()->getResource('Driver.'.$name);
    }

    /**
     * runCommonEvent.
     *
     * @param mixed $name
     */
    public function runCommonEvent($name)
    {
        try {
            //LnxMcpFullDebugOn();
            $argin = $this->getMcp()->getCommon();
            $argin['T'] = 'common';
            $argin['E'] = $name;
            $this->debug('Common Event:'.print_r($argin, 1));
            $res = $this->run($this->bootCtl, $argin);
            if (isset($res['return'])) {
                $res = $res['return'];
            }
            $this->debug('Common '.$name.':'.print_r($res, 1));
            $this->getMcp()->setCommon($name, $res);
        } catch (\ErrorException $e) {
            $this->warning($e->getMessage());
        }
    }

    /**
     * runCommonEvent.
     *
     * @param mixed $name
     *
     * @return array
     */
    public function runEvent($type, $name, $scopeIn)
    {
        $scopeIn['T'] = $type;
        $scopeIn['E'] = $name;

        return $this->run($this->argCtl, $scopeIn);
    }

    /**
     * In this service class is premanaged the module core as reflection calling
     * so  inf asking an event this call the specific method if is present the order
     *  (T)type method
     *  (E)ventname method
     *  type_name method.
     */
    protected function moduleCore()
    {
        $this->debug('moduleCore');
        $method = '';
        if (!empty($this->argIn['T'])) {
            $method .= $this->argIn['T'];
            $funcx = $this->argIn['T'];
            if (method_exists($this, $funcx)) {
                $this->debug('moduleCore>>'.$funcx);
                $this->$funcx();
            } else {
                $this->debug('Not Found>>'.$funcx);
            }
        }
        if (!empty($this->argIn['E'])) {
            if (!empty($method)) {
                $method .= '_';
            }
            $method .= $this->argIn['E'];
            $funcx = $this->argIn['E'];
            if (method_exists($this, $funcx)) {
                $this->debug('moduleCore>>'.$funcx);
                $this->$funcx();
            } else {
                $this->debug('Not Found>>'.$funcx);
            }
        }
        if (!empty($method)) {
            if (method_exists($this, $method)) {
                $this->debug('found:moduleCore>>'.$method);
                $this->$method();
            } else {
                $this->debug('Not Found>>'.$method);
            }
        }
    }

    /**
     * @param array (reference of) $scopeCtl => calling Controlling definitions
     * @param array (reference of) $scopeIn  temproraney array auto cleanable
     */
    public function __construct(masterControlProgram &$mcp, array $scopeCtl, array $scopeIn)
    {
        parent::__construct($mcp, $scopeCtl, $scopeIn);
        $this->argCfg = $scopeCtl;
        $cfgfile = ''.str_replace('\\', '_', $this->spacename);
        $this->debug('get config from '.$cfgfile);
        $cfgpath = $mcp->getCfg('app.path.config');
        $cfgarr = lnxGetJsonFile($cfgfile, $cfgpath, 'json');
        if (is_array($cfgarr)) {
            foreach ($cfgarr as $k => $v) {
                $this->argCfg[$k] = $v;
            }
        }
        $this->debug('CommonEventInit');
        $this->runCommonEvent('Init');
    }
}
