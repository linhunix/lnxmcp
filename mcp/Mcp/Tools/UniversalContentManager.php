<?php
/**
 * LinHUniX Web Application Framework.
 *
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 *
 * @version GIT:2018-v2
 */

namespace LinHUniX\Mcp\Tools;

/**
 * This class is used to manage the universal content manager.
 */
class UniversalContentManager
{
    /**
     * run function.
     *
     * @param string $url = if is null get data for request
     */
    private $file;
    private $w;
    private $h;
    private $tag;
    private $ranges;
    private $ext;
    private $path;
    private $jsonpath;
    private $folder;
    private $allow;
    private $convert;
    private $remote_dynamic;
    private $remote_cache;
    private $remote_url;
    private $remote_img;
    private $mode;

    /**
     * load config from mcp.
     */
    private function loadCfg()
    {
        $this->path = lnxmcp()->getResource('ucm.path');
        if ($this->path == null) {
            $this->path = lnxmcp()->getResource('path');
        }
        $this->jsonpath=lnxmcp()->getResource("path.exchange");
        $this->allow = lnxmcp()->getResource('ucm.allow');
        $this->convert = lnxmcp()->getResource('ucm.convert');
        if ($this->convert == null) {
            $this->convert = false;
        }
        $this->ranges = lnxmcp()->getResource('ucm.ranges');
        $this->remote_cache = lnxmcp()->getResource('ucm.cache');
        if ($this->remote_cache == null) {
            $this->remote_cache = false;
        }
        $this->remote_url = lnxmcp()->getResource('redirect_404_url');
        $this->remote_img = lnxmcp()->getResource('redirect_404_img');
        $this->remote_dynamic = lnxmcp()->getResource('ucm.dynamic');
        if ($this->remote_dynamic == null) {
            $this->remote_dynamic = false;
            if ($this->remote_url != null) {
                $this->remote_dynamic = true;
            }
        }
    }

    /**
     * loadScope function call manage the scope.
     *
     * @param array $scopein
     */
    private function loadScope(array $scopein)
    {
        $this->w = 0;
        $this->h = 0;
        $this->file = $scopein['REQUEST_URI'];
        if (isset($scopein['file'])) {
            $this->file = $scopein['file'];
        }
        if (isset($scopein['tag'])) {
            $this->tag = $scopein['tag'];
        } elseif (isset($scopein['size'])) {
            $this->tag = $scopein['size'];
        }
        if (isset($scopein['w'])) {
            $this->w = intval($scopein['w']);
        }
        if (isset($scopein['h'])) {
            $this->h = intval($scopein['h']);
        }
        if (isset($scopein['folder'])) {
            $this->folder = $scopein['folder'];
        } else {
            $earr = explode('/', $this->file);
            $this->file = array_pop($earr);
            $this->folder = implode('/', $earr) . '/';
            unset($earr);
        }
        if (isset($scopein['ext'])) {
            $this->ext = $scopein['ext'];
        } else {
            $earr = explode('.', $this->file);
            $this->ext = array_pop($earr);
            unset($earr);
        }
        if (isset($scopein['U404'])) {
            $this->remote_url = $scopein['U404'];
        }
        $this->mode = 'live';
        if (isset($scopein['mode'])) {
            $this->background = $scopein['mode'];
        }
    }

    /**
     * isallow function check if is allow or not a specific extension.
     *
     * @return bool
     */
    private function checkAllow()
    {
        if (is_array($this->allow)) {
            if (isset($this->allow[$this->ext])) {
                if (is_array($this->allow[$this->ext])) {
                    if (isset($this->allow[$this->ext]['folder'])) {
                        $this->folder = $this->allow[$this->ext]['folder'];
                    }
                    if (isset($this->allow[$this->ext]['path'])) {
                        $this->path = $this->allow[$this->ext]['path'];
                    }
                    if (isset($this->allow[$this->ext]['convert'])) {
                        $this->convert = $this->allow[$this->ext]['convert'];
                    }
                    if (isset($this->allow[$this->ext]['tag'])) {
                        $this->tag = $this->allow[$this->ext]['tag'];
                    }
                    if (isset($this->allow[$this->ext]['dynamic'])) {
                        $this->remote_dynamic = $this->allow[$this->ext]['dynamic'];
                    }
                    if (isset($this->allow[$this->ext]['redirect_404_url'])) {
                        $this->remote_url = $this->allow[$this->ext]['redirect_404_url'];
                    }
                    if (isset($this->allow[$this->ext]['redirect_404_img'])) {
                        $this->remote_img = $this->allow[$this->ext]['redirect_404_img'];
                    }
                    if (isset($this->allow[$this->ext]['cache'])) {
                        $this->remote_cache = $this->allow[$this->ext]['cache'];
                    }
                    if (isset($this->allow[$this->ext]['ranges'])) {
                        $this->ranges = $this->allow[$this->ext]['ranges'];
                    }
                }
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * checkFilePresent function check if exist local the file.
     *
     * @return string filename
     */
    private function checkFilePresent()
    {
        $filebase=$this->path."/".$this->folder;
        if (($this->w === 0) && ($this->h === 0)) {
            $this->convert = false;
        }
        $filename = $filebase . $this->tag . $this->file;
        // check if file exitst
        if (file_exists($filename)) {
            $this->convert = false;

            return $filename;
        }
        $filename = $filebase . $this->w . 'x' . $this->h . $this->file;
        if (file_exists($filename)) {
            $this->convert = false;

            return $filename;
        }
        if ($this->convert == true) {
            if (is_array($this->ranges)) {
                foreach ($this->ranges as $rtag => $rvalue) {
                    if (strstr($rtag, 'x')) {
                        if (isset($rvalue['wmax'])) {
                            $rvalue['wmax'] = 0;
                        }
                        $rwmax = intval($rvalue['wmax']);
                        if (isset($rvalue['wmin'])) {
                            $rvalue['wmin'] = 0;
                        }
                        $rwmin = intval($rvalue['wmin']);
                        if (isset($rvalue['hmax'])) {
                            $rvalue['hmax'] = 0;
                        }
                        $rhmax = intval($rvalue['hmax']);
                        if (isset($rvalue['hmin'])) {
                            $rvalue['hmin'] = 0;
                        }
                        $rhmin = intval($rvalue['hmin']);
                        if (($this->h >= $rhmin) && ($this->h <= $rhmax) && ($this->w >= $rwmin) && ($this->w <= $rwmax)) {
                            $this->tag = $rtag;
                            $rarr = explode('x', $rtag);
                            $this->w = $rarr[0];
                            $this->h = $rarr[1];
                        }
                    } else {
                        if (isset($rvalue['max'])) {
                            $rvalue['max'] = 0;
                        }
                        $rmax = intval($rvalue['max']);
                        if (isset($rvalue['min'])) {
                            $rvalue['min'] = 0;
                        }
                        $rmin = intval($rvalue['min']);
                        if ($rvalue == 'h') {
                            if (($this->h >= $rmin) && ($this->h <= $rmax)) {
                                $this->h = $rtag;
                                $this->w = 0;
                            }
                        } else {
                            if (($this->w >= $rmin) && ($this->w <= $rmax)) {
                                $this->w = $rtag;
                                $this->h = 0;
                            }
                        }
                    }
                }
            }
        }
        $filename = $filebase . $this->tag . $this->file;
        if (file_exists($filename)) {
            $this->convert = false;

            return $filename;
        }
        $filename = $filebase . $this->w . 'x' . $this->h . $this->file;
        if (file_exists($filename)) {
            $this->convert = false;

            return $filename;
        }
        if ($this->convert == true) {
            if ($this->w < $this->h) {
                $this->w = 0;
                $filename = $filebase . $this->w . 'x' . $this->h . $this->file;
                if (file_exists($filename)) {
                    $this->convert = false;

                    return $filename;
                }
            }
        }
        if ($this->convert == true) {
            if ($this->w > $this->h) {
                $this->h = 0;
                $filename = $filebase . $this->w . 'x' . $this->h . $this->file;
                if (file_exists($filename)) {
                    $this->convert = false;

                    return $filename;
                }
            }
        }
        $filename = $filebase . $this->file;
        if (file_exists($filename)) {
            return $filename;
        }

        return null;
    }

    /**
     * callRedirect function.
     * manage the redirect funcion.
     */
    private function callRedirect()
    {
        lnxMcpTag('UCM-NOT-FOUND');
        if (($this->remote_url != null) && ($this->redirect_img != null)) {
            if ($this->remote_url != 'IMG') {
                lnxmcp()->header('Location: ' . $this->remote_url, true);
            }
        }
        if ($this->remote_dynamic) {
            if ($this->remote_url != null) {
                lnxmcp()->header('Location: ' . $this->remote_url . $this->folder . '/' . $this->file, true);
            }
        }
        if ($this->remote_url != null) {
            lnxmcp()->header('Location: ' . $this->remote_url, true);
        }
        if ($this->remote_img != null) {
            lnxmcp()->header('Location: ' . $this->remote_img, true);
        }
        lnxmcp()->header('HTTP/1.0 404 Not Found', true, 404);
        exit;
    }

    /**
     * callSendFile send file to  live 
     *
     * @param  mixed $filename
     *
     * @return void
     */
    private function callSendFile($filename)
    {
        if (file_exists($filename)) {
            $filename = realpath($filename);
            $dirname = dirname($filename);
            $mime = mime_content_type($filename);
            $size = filesize($filename);
            lnxmcp()->debug('file:' . $filename . ' - mime:' . $mime . ' - size:' . $size);
            lnxmcp()->header('Content-Type: ' . $mime, false);
            echo file_get_contents($path . $_SERVER['REQUEST_URI']);
            return;
        }
        $this->callRedirect();
    }


    /**
     * writeConvertRequest
     *
     * @return void
     */
    private function writeConvertRequest () {
        $req=array(
            "mode"=>"background",
            "file"=>$this->file,
            "folder"=>$this->folder,
            "ext"=>$this->ext,
            "tag"=>$this->tag,
            "w"=>$this->w,
            "h"=>$this->h
        );
        lnxPutJsonFile($req,$file,$this->jsonpath,"json");
    }

    /**
     * executeConvertRequest
     *
     * @return void
     */
    private function executeConvertRequest ($filedest) {
        $filesource=$this->path."/".$this->folder."/".$this->file;
        lnxMcpCmd(
            array(
                'type' => 'serviceCommon',
                'name' => 'gfx',
                'module' => 'Gfx',
            ),
            array(
                'T' => 'IMG',
                'effect' => 'resize',
                'source' => $filesource,
                'dest' => $filedest,
                'width' => $this->w,
                'height' => $this->h,
            )
        );
    }

    /**
     * run script 
     *
     * @return void
     */
    private function run()
    {
        $live = true;
        $batch = false;
        switch ($this->mode) {
            case 'background':
                $live = false;
                $batch = true;
                break;;
            case 'live':
                $live = true;
                $batch = false;
                break;;
            case 'all':
                $live = true;
                $batch = true;
                break;;
        }
        if ($this->checkAllow() == false) {
            if ($live == true) {
                $this->callRedirect();
            }
            return;
        }
        $loadfile = $this->checkFilePresent();
        if ($loadfile == null) {
            if ($live == true) {
                $this->callRedirect();
            }
            return;
        }
        if ($live == true) {
            $this->callSendFile($loadfile);
        }
        if ($this->convert==true){
            if ($batch == false) {
                $this->writeConvertRequest();
            } else {
                $this->executeConvertRequest($loadfile
            );
            }
        }
    }
    /**
     * UniversalContentManager __construct function.
     *
     * @param string $file
     * @param array  $scopein
     */
    public function __construct($scopein = null)
    {
        $this->loadCfg();
        if ($scopein == null) {
            $scopein = $_REQUEST;
        }
        if (!is_array($scopein)) {
            $scopein=lnxGetJsonFile($scopein,$this->jsonpath,"json");
        }
        $this->loadScope($scopein);
        $this->run();
    }
}
