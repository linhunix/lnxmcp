<?php
/**
 * User: LinHUniX Andrea Morello
 * Date: 1/12/2019
 * Time: 9:22 PM.
 */
class mcpOutput {
    /**
     * @var mcpOutput 
     * static instance
     */
    private static $lnxMcpOut=null;
    /**
     * private constructor tipical of the singleton logic 
     */
    private function __construct()
    {
        
    }
    /**
     * call the singleton instance 
     */
    public static function getInstance(){
        if (self::$lnxMcpOut==null) {
            self::$lnxMcpOut= new mcpOutput();
        }
        return self::$lnxMcpOut;
    }
    /**
     * MimeFile replace the mime_content_type.
     *
     * @param string $filename
     * @param string $defaultmime
     *
     * @return string content-type
     */
    public function mimeFile($filename, $defaultmime = null){
        if (class_exists('finfo')) {
            $result = new \finfo();
            if (is_resource($result) === true) {
                return $result->file($filename, FILEINFO_MIME_TYPE);
            }
        } elseif (function_exists('mime_content_type')) {
            return \mime_content_type($filename);
        }
        if ($defaultmime != null) {
            return $defaultmime;
        }
    
        return 'application/octet-stream';
    }
    /**
     * MimeExt replace search by ext or by filecontent the mime content-type.
     *
     * @param string $filename
     * @param string $ext (file extension)
     *
     * @return string content-type     
     */
    public function mimeExt($filename,$ext){
        $mime=null;
        if (file_exists($filename)) {
            $mime = 'text/html';
            switch ($ext) {
            case 'tpl':
            case 'html':
                break;
            case 'css':
                $mime = 'text/css';
                break;
            case 'js':
                $mime = 'text/js';
                break;
            case 'jpg':
            case 'jpg':
                $mime = 'image/jpg';
                break;
            case 'gif':
                $mime = 'image/gif';
                break;
            case 'png':
                $mime = 'image/png';
                break;
            default:
                $mime = $this->mimeFile($filename,$mime);
            }
        }
        return $mime;
    }
     /**
     * Search and convert the in path to the real sys path
     * @param string $ext
     * @param string $file
     * @return string $path
     */
    public function getRealExt($ext,$file){
        switch ($ext){
            case 'fromFile':
                if (empty($file)){
                    return null;
                }
                $arrfile=explode('.',$file);
                if ($arrfile[0]!=end($arrfile)){
                    return end($arrfile);
                }
            break;
            case 'fromExtUrl':
                return lnxmcp()->getCommon("ExtUrl");
            break;
            case 'fromEndUrl':
                $endurl=lnxmcp()->getCommon("EndUrl");
                return lnxmcp()->getResource("ext.".$endurl);
            break;
            default:
            return $ext;
        }
    }
     /**
     * Search and convert the in path to the real sys path
     * @param string $ext
     * @param string $file
     * @return string $path
     */
    public function getRealFile($file,$scopeIn){
        switch ($file){
            case 'fromPathUrl':
                return lnxmcp()->getCommon("PathUrl");
            break;
            case 'fromEndUrl':
                return lnxmcp()->getCommon("EndUrl");
            break;
            case 'fromScopeIn':
                if (!is_array($scopeIn)){
                    return null;
                }
                foreach ($scopeIn as $k=>$v){
                    $file=str_replace('['.$k."]",$v,$file);
                }
                return $file;
            break;
            default:
            return $file;
        }
    }
    /**
     * Search and convert the in path to the real sys path
     * @param string $path
     * @return string $path
     */
    public function getRealPath($path ){
        switch ($path) {
            case 'app.path':
                $path = lnxmcp()->getResource('path');
                break;
            case 'app.path.module':
                $path = lnxmcp()->getResource('path.module');
                break;
            case 'app.path.template':
                $path = lnxmcp()->getResource('path.template');
                break;
            case 'mcp.path.root':
                $path = lnxmcp()->getCfg('mcp.path.root');
                break;
            case 'mcp.path':
                $path = lnxmcp()->getCfg('mcp.path');
                break;
            case 'app.path.workjob':
                $path = lnxmcp()->getResource('path.workjob');
                break;
            case 'app.path.exchange':
                $path = lnxmcp()->getResource('path.exchange');
                break;
            case 'app.path.language':
                $path = lnxmcp()->getResource('path.language');
                break;
        }
        if ( (!file_exists($path)) and (!is_dir($path)) ) {
            $app_path = lnxmcp()->getResource('path');
            if ( (file_exists($app_path.DIRECTORY_SEPARATOR.$path)) or (is_dir($app_path.DIRECTORY_SEPARATOR.$path)) ){
                $pathtmp=$app_path.DIRECTORY_SEPARATOR.$path;
                $path=$pathtmp;
            }
        }
        $hfile = realpath($path);
        if ($hfile == '') {
            $hfile = $path;
        }
        return $hfile;
    }
    /**
     * linhunix External file load and converter.
     *
     * @param string $file
     * @param string $path    if is need
     * @param string $ext     with out the '.'
     * @param array $scopeIn array in
     * @param boolean $convert if need to  convert or  only load
     * @param boolean $runphp if need to  execute php or only exclude
     *
     * @return bool
     */

    public function loadExtFile($file, $path = '', $ext = null, $scopeIn = array(), $convert = true, $runphp=false)
    {
        /// DEFINE PATH
        $hfile=$this->getRealPath($path);
        lnxmcp()->debugvar('lnxMcpExtLoad', 'path', $path);
        // CHECK FILE
        $file=$this->getRealFile($file);
        // ADD FILE 
        if ($hfile != '') {
            $hfile .= DIRECTORY_SEPARATOR.$file;
        }
        // CHECK EXT
        $ext=$this->getRealExt($ext,$file);
        // ADD EXT 
        if (!empty($ext)) {
            $hfile .= '.'.$ext;
        }
        lnxmcp()->info('lnxMcpExtLoad try to load and convert :'.$hfile);
        /// SEARCH MIME 
        $mime=$this->mimeExt($hfile,$ext);
        /// IF FILE NOT EXIST MIME IS NULL;
        if ($mime==null){
            lnxmcp()->info('lnxHtmlPage>>file:'.$hfile.' and not found');
            return false;
        }
        /// SET HEADER 
        lnxmcp()->header('Content-Type: '.$mime, false, 200);
        // CASE PHP 
        if ($ext=='php' ){
            if ($runphp==false){
                return false;
            }
            try {
                lnxmcp()->info('lnxMcpExtLoad:(with Convert)'.$hfile);
                ob_start();
                include $hfile;
                return ob_get_clean();
            } catch (\Exception $e) {
                lnxmcp()->warning('lnxMcpExtLoad>>Php file:'.$hfile.' and err:'.$e->getMessage());
                return false;
            }
        }
        // CASE CONVERT
        try {
            if ($convert == true) {
                lnxmcp()->info('lnxMcpExtLoad:(with Convert)'.$hfile);
                return lnxmcp()->converTag(file_get_contents($hfile), $scopeIn);
            }
            lnxmcp()->info('lnxMcpExtLoad:(without Convert)'.$hfile);
            return file_get_contents($hfile);        
        } catch (\Exception $e) {
            lnxmcp()->warning('lnxMcpExtLoad>>file:'.$hfile.' and err:'.$e->getMessage());
            return false;
        }
        /// for many question a probably 1 things goes wrong 
        return false;
    }
}