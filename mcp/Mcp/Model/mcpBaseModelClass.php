<?php

/**
 * LinHUniX Web Application Framework
 *
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 */

namespace LinHUniX\Mcp\Model;

use LinHUniX\Mcp\masterControlProgram;

/**
 * @see Head.php caller of the config
 * @see ftSimpleMCP Master Control Program
 */
class mcpBaseModelClass {

    /**
     * on this class need to set the number of the dependency need to use to work with this
     * class ant that information was stored on $scopeCtl and share when execute this specific modules
     * @example $require=array("app.page","app.config");
     * @var array;
     */
    protected $require;

    /**
     * @var bool run only on start
     */
    protected $singleTon;

    /**
     *
     * @var array contains the info of the data class control
     */
    protected $bootCtl;

    /**
     *
     * @var array contains the boot data informations
     */
    protected $bootData;

    /**
     * used this to set Control Argument during run of the moduleCore
     * @var array
     */
    protected $argCtl;

    /**
     * used this to set Input argument during run of the moduleCore
     * @var array
     */
    protected $argIn;

    /**
     * used this to set output argument during run of the moduleCore
     * is present on the scope auto as $scopeOut["return"];
     * @var array
     */
    protected $argOut;

    /**
     * need for the call back
     * @var masterControlProgram
     */
    protected $mcp;

    /**
     * @param array                (reference of) $scopeCtl => calling Controlling definitions
     * @param array                (reference of) $scopeIn temproraney array auto cleanable
     * @param masterControlProgram to call back the father
     */
    public function __construct (masterControlProgram &$mcp, array $scopeCtl, array $scopeIn)
    {
        $this->mcp = $mcp;
        $this->bootCtl = $scopeCtl;
        $this->bootData = $scopeIn;
        $this->require = array();
        $this->singleTon=false;
        if (isset($scopeIn["public"])) {
            if (is_array($scopeIn["public"])) {
                foreach ($scopeIn["public"] as $tag => $value) {
                    $this->{$tag} = $value;
                }
                unset($scopeIn["public"]);
            }
        }
    }

    /**
     * Return a list of dependency need to used this class (cfg component)
     * @return array
     */
    public function getDependency() {
        return $this->require;
    }

    /**
     * Model Base to caputer execute an elabotrations about this
     * @param array (reference of) $scopeCtl => calling Controlling definitions
     * @param array (reference of) $scopeIn temproraney array auto cleanable
     * @return array response of code = like scope out;
     */
    public function run(array $scopeCtl, array $scopeIn) {

        $this->argCtl = $scopeCtl;
        $this->argIn = $scopeIn;
        $this->argOut = array();
        if ($this->singleTon==false){
            $this->singleTon=true;
            $this->moduleSingleTon();
        }
        $this->moduleCore();
        $this->getMcp()->rstScopeOut();
        $this->setReturn($this->argOut);
        return $this->getMcp()->getScopeOut();
    }

    /**
     * this a metod fo call the mcp class functions
     * @return lnxmcp class
     */
    protected function getMcp() {
        return $this->mcp;
    }

    /**
     * this a  metod fo call the mcp class functions
     * @return lnxmcp class
     */
    protected function getDriver($drvlabel) {
        return $this->mcp->getResource("Driver.".$drvlabel);
    }

    /**
     * this a  metod fo call the mcp class functions
     * @return lnxmcp class
     */
    protected function getCommon($name) {
        return $this->mcp->getCommon($name);
    }

    /**
     * Is a confortable method to set data on scope out
     * @param type $name of the tags
     * @param type $value
     */
    protected function setScopeOut($name, $value) {
        $this->getMcp()->setScopeOut($name, $value);
    }

    /**
     * is a confortable method to set the return values
     * @param type $return
     */
    protected function setReturn($return) {
        $this->setScopeOut("return", $return);
    }

    /**
     *  Ideally this method shuld be used to insert the model code and the other are to be used only as normal
     */
    protected function moduleCore() {
        /// is empty waith to be implemented 
    }

    /**
     *  Ideally this method shuld be used to insert the model code and the other are to be used only as normal
     */
    protected function moduleSingleTon() {
        /// is empty waith to be implemented 
    }


    /**
     * only to have a confortable solutions to get data
     * @param string $name
     * @return any
     */
    protected function getArgIn($name) {
        if (isset($this->argIn[$name])) {
            return $this->argIn[$name];
        }
        return null;
    }

    /**
     * only to have a confortable solutions to get data
     * @param string $name
     * @return any
     */
    protected function getArgCtl($name) {
        if (isset($this->argCtl[$name])) {
            return $this->argCtl[$name];
        }
        return null;
    }

    /**
     * only to have a confortable solutions to get data
     * @param string $name
     * @return any
     */
    protected function getArgOut($name) {
        if (isset($this->argOut[$name])) {
            return $this->argOut[$name];
        }
        return null;
    }

    /**
     * only to have a confortable solutions to set data
     * @param string $name
     * @return any
     */
    protected function setArgOut($name, $value) {
        return $this->argOut[$name] = $value;
    }

}
