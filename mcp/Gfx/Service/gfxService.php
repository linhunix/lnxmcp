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
        $gfxdefm=$this->getMcp()->getResource("gfx.default.menu");
        if (! empty ($gfxdefm)){
            if (is_array($gfxdefm)){
                foreach ($gfxdefm as $menutpl){
                    $this->loadMenusCommon("Gfx/".$menutpl."/mnu/default");
                }
            }else{
                $this->loadMenusCommon("Gfx/".$gfxdefm."/mnu/default");
            }
        }
        $gfxdeft=$this->getMcp()->getResource("gfx.default.tag");
        if (! empty ($gfxdeft)){
            if (is_array($gfxdeft)){
                foreach ($gfxdeft as $tagtpl){
                    $this->loadMenusCommon("Gfx/".$tagtpl."/tag/default");
                }
            }else{
                $this->loadMenusCommon("Gfx/".$gfxdeft."/tag/default");
            }
        }
    }

    private function getInternalPath(){
        try{
            if ($this->getMcp()->getCfg("phar")==true){
                return $this->getMcp()->getCfg("purl")."mcp/";
            } else {
                return $this->getMcp()->getCfg("mcp.path");
            }
        }catch (Exception $e){
            return "";
        }
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
     * callStaticCommon
     *
     * @param  String $InternalSource content 
     * @param  String $MimeType
     * @param  Bool $ConverTag
     * @param  array $arg 
    * @return void 
     */
    public function callStaticCommon($InternalSource, $MimeType,$ConverTag=false,$arg=array())
    {
        try{
            $purl=$this->getInternalPath();
            $res=file_get_contents($purl.DIRECTORY_SEPARATOR.$InternalSource);
            \header('Content-type: '.$MimeType);
            if ($ConverTag==true){
                echo $this->getMcp()->covertTag($res,$arg);
            } else {
                echo $res;
            }
        }catch (\Exception $e){
            $this->getMcp()->error("Gfx->callStaticCommon:".$e->getMessage());
            $this->getMcp()->NotFound($InternalSource);
        }
    }
    /**
     * callDynamicCommon
     *
     * @param  String $InternalSource content 
     * @param  String $MimeType
     * @param  Bool $ConverTag
     * @param  array $arg 
    * @return void 
     */

    public function callDynamicCommon($InternalSource, $MimeType,$ConverTag=false,$arg=array())
    {
        try{
            $purl=$this->getInternalPath();
            ob_start();
            include $purl.$InternalSource.".tpl";
            $res=ob_get_clean();
            \header('Content-type: '.$MimeType);
            if ($ConverTag==true){
                echo $this->getMcp()->covertTag($res,$arg);
            } else {
                echo $res;
            }
        }catch (\Exception $e){
            $this->getMcp()->error("Gfx->callDynamicCommon:".$e->getMessage());
            $this->getMcp()->NotFound($InternalSource);
        }
    }

    /**
     * loadMenusCommon
     *
     * @param  String $InternalSource content 
     * @return void 
     */
    public function loadMenusCommon($InternalSource){
        try{
            $purl=$this->getInternalPath();
            $res=json_decode(file_get_contents($purl.$InternalSource.".mnu.json"),true);
            if (is_array($res)){
                foreach($res as $menu =>$sequence){
                    $this->getMcp()->Debug("loadTagsCommon load menu:".$menu);
                    $this->getMcp()->setCfg("app.menu.".$menu,$sequence);
                }
            }
        }catch (\Exception $e){
            $this->getMcp()->error("Gfx->loadMenusCommon:".$e->getMessage());
            $this->getMcp()->NotFound($InternalSource);
        }
    }
    /**
     * loadTagsCommon
     *
     * @param  String $InternalSource content 
     * @return void 
     */
    public function loadTagsCommon($InternalSource){
        try{
            $purl=$this->getInternalPath();
            $res=json_decode(file_get_contents($purl.$InternalSource.".tag.json"),true);
            if (is_array($res)){
                foreach($res as $tag =>$sequence){
                    $this->getMcp()->Debug("loadTagsCommon load tag:".$tag);
                    $this->getMcp()->setCfg("app.tags.".$tag,$sequence);
                }
            }
        }catch (\Exception $e){
            $this->getMcp()->error("Gfx->loadMenusCommon:".$e->getMessage());
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
                $mime = @$this->argIn["minetype"];
                $tag= @$this->argIn["tag"];
                $this->argOut = $this->callStaticCommon($source,$mime,$tag,$this->argIn);
                break;
             case "DYN":
                if (!isset($this->argIn["source"])) {
                    return;
                }
                $source = $this->argIn["source"];
                $mime = @$this->argIn["minetype"];
                $tag= @$this->argIn["tag"];
                $this->argOut = $this->callDynamicCommon($source,$mime,$tag,$this->argIn);
                break;
            case "MNU":
                if (!isset($this->argIn["source"])) {
                    return;
                }
                $source = $this->argIn["source"];
                $this->argOut = $this->loadMenusCommon($source,$mime,$tag,$this->argIn);
                break;
        }
    }
}

