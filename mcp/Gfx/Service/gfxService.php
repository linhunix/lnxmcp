<?php

/**
 * LinHUniX Web Application Framework
 *
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 */

namespace LinHUniX\Gfx\Service;

use LinHUniX\Mcp\Model\mcpBaseModelClass;
use LinHUniX\Mcp\masterControlProgram;

class gfxService extends mcpBaseModelClass
{
    private static $html2txt = null;

    /**
     * @param array (reference of) $scopeCtl => calling Controlling definitions  
     * @param array (reference of) $scopeIn temproraney array auto cleanable 
     */
    public function __construct(masterControlProgram &$mcp, array $scopeCtl, array $scopeIn)
    {
        parent::__construct($mcp, $scopeCtl, $scopeIn);
    }
    /**
     * getHtml2Txt
     *
     * @param  String $source html content 
     * @param  String $from_file
     * @return String text converted
     */
    public function getHtml2Txt($source = '', $from_file = false)
    {
        if (self::$html2txt == null) {
            include_once __DIR__ . "./../Component/html2text.class.php";
            self::$html2txt = new \html2text();
        }
        self::$html2txt->set_html($source, $from_file);
        self::$html2txt->set_base_url();
        return self::$html2txt->get_text();
    }
    /**
     * getStaticCommon
     *
     * @param  String $InternalSource content 
     * @param  String $MimeType
     * @param  Bool $ConverTag
     * @param  array $arg 
    * @return void 
     */
    public function getStaticCommon($InternalSource, $MimeType,$ConverTag=false,$arg=array())
    {
        try{
        if ($this->getMcp()->getCfg("phar")==true){
            $purl=$this->getMcp()->getCfg("purl");
        } else {
            $purl=$this->getMcp()->getCfg("mcp.path");
        }
        $res=file_get_contents($purl.DIRECTORY_SEPARATOR.$InternalSource);
        \header('Content-type: '.$MimeType);
        if ($ConverTag==true){
            echo $this->getMcp()->covertTag($res,$arg);
        } else {
            echo $res;
        }
        }catch (\Exception $e){
            $this->getMcp()->error("Gfx->getStaticCommon:".$e->getMessage());
            $this->getMcp()->NotFound($InternalSource);
        }
    }
    /**
     * $scopeIN array is 
     * var "[T]"
     * -- H2T =html to Text convert
     *      var ["html"] = stored in globals 
     *      return txt converson 
     * -- INT =Internal Static Source
     *      var ["source"] = Internal Source File
     *      var ["minetype"] = Mime Type of Internal Source File
     *      var ["tag"] = Use Tag Converter
     *      return txt converson 
     * @author Andrea Morello <andrea.morello@linhunix.com>
     * @version GIT:2018-v1
     * @param array $this->argIn temproraney array auto cleanable 
     * @return boolean|array query results 
     */
    public function moduleCore()
    {
        if (!isset($this->argIn["T"])) {
            return;
        }
        $this->getMcp()->Debug("GfxService Call:".$this->argIn["T"]);
        switch ($this->argIn["T"]) {
            case "H2T":
                if (!isset($this->argIn["html"])) {
                    return;
                }
                $html = $this->argIn["html"];
                $this->argOut = $this->getHtml2Txt($html);
                break;
            case "INT":
                if (!isset($this->argIn["source"])) {
                    return;
                }
                $source = $this->argIn["source"];
                $mime = $this->argIn["minetype"];
                $tag= $this->argIn["tag"];
                $this->argOut = $this->getStaticCommon($source,$mime,$tag,$this->argIn);
                break;
        }
    }
}

