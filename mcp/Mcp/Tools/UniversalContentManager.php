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
    /////////////////////////////////////////////////////////////////////////////////////
    /// VARIABLES
    /////////////////////////////////////////////////////////////////////////////////////
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
    private $head_expire_cache;
    private $remote_dynamic;
    private $remote_cache;
    private $remote_url;
    private $remote_img;
    private $action_load;
    private $action_live;
    private $action_batch;
    private $action_cache;
    private $action_redirect;
    private $mode;

    /////////////////////////////////////////////////////////////////////////////////////
    // PRIVATE FUNCTION - LOAD CONFIG
    /////////////////////////////////////////////////////////////////////////////////////

    /**
     * load config from mcp.
     */
    private function loadCfg()
    {
        $this->path = lnxmcp()->getResource('ucm.path');
        if ($this->path == null) {
            $this->path = lnxmcp()->getResource('path');
        }
        $this->jsonpath = lnxmcp()->getResource('path.exchange');
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
        $this->head_expire_cache = lnxmcp()->getResource('ucm.expirecache');
        if ($this->head_expire_cache == null) {
            $this->head_expire_cache = '60';
        }
        $this->action_load = lnxmcp()->getResource('ucm.action.load');
        $this->action_live = lnxmcp()->getResource('ucm.action.live');
        $this->action_batch = lnxmcp()->getResource('ucm.action.batch');
        $this->action_redirect = lnxmcp()->getResource('ucm.action.cache');
        $this->action_redirect = lnxmcp()->getResource('ucm.action.redirect');
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
        $this->file = @$scopein['REQUEST_URI'];
        $obj = lnxGetJsonFile($this->file, $this->path, 'json');
        if (is_array($obj)) {
            foreach ($obj as $ok => $ov) {
                $scopein[$ok] = $ov;
            }
        }
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
            $this->folder = implode('/', $earr).'/';
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
        if (isset($scopein['expire'])) {
            $this->head_expire_cache = $scopein['expire'];
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
                    if (isset($this->allow[$this->ext]['ranges'])) {
                        $this->ranges = $this->allow[$this->ext]['ranges'];
                    }
                    if (isset($this->allow[$this->ext]['expire'])) {
                        $this->head_expire_cache = $this->allow[$this->ext]['expire'];
                    }
                    if (isset($this->allow[$this->ext]['convert'])) {
                        $this->convert = $this->allow[$this->ext]['convert'];
                    }
                    if (isset($this->allow[$this->ext]['cache'])) {
                        $this->remote_cache = $this->allow[$this->ext]['cache'];
                    }
                    if (isset($this->allow[$this->ext]['action_load'])) {
                        $this->action_load = $this->allow[$this->ext]['action_load'];
                    }
                    if (isset($this->allow[$this->ext]['action_live'])) {
                        $this->action_live = $this->allow[$this->ext]['action_live'];
                    }
                    if (isset($this->allow[$this->ext]['action_batch'])) {
                        $this->action_batch = $this->allow[$this->ext]['action_batch'];
                    }
                    if (isset($this->allow[$this->ext]['action_cache'])) {
                        $this->action_cache = $this->allow[$this->ext]['action_cache'];
                    }
                    if (isset($this->allow[$this->ext]['action_redirect'])) {
                        $this->action_redirect = $this->allow[$this->ext]['action_redirect'];
                    }
                }
            } else {
                return false;
            }
        }

        return true;
    }

    /////////////////////////////////////////////////////////////////////////////////////
    // PRIVATE FUNCTION - VERIFIY FILE
    /////////////////////////////////////////////////////////////////////////////////////

    /**
     * checkFilePresent function check if exist local the file.
     *
     * @return string filename
     */
    private function checkFilePresent()
    {
        $filebase = $this->path.'/'.$this->folder;
        if (($this->w === 0) && ($this->h === 0)) {
            $this->convert = false;
        }
        $filename = $filebase.$this->tag.$this->file;
        // check if file exitst
        if (file_exists($filename)) {
            $this->convert = false;

            return $filename;
        }
        $filename = $filebase.$this->w.'x'.$this->h.$this->file;
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
        $filename = $filebase.$this->tag.$this->file;
        if (file_exists($filename)) {
            $this->convert = false;

            return $filename;
        }
        $filename = $filebase.$this->w.'x'.$this->h.$this->file;
        if (file_exists($filename)) {
            $this->convert = false;

            return $filename;
        }
        if ($this->convert == true) {
            if ($this->w < $this->h) {
                $this->w = 0;
                $filename = $filebase.$this->w.'x'.$this->h.$this->file;
                if (file_exists($filename)) {
                    $this->convert = false;

                    return $filename;
                }
            }
        }
        if ($this->convert == true) {
            if ($this->w > $this->h) {
                $this->h = 0;
                $filename = $filebase.$this->w.'x'.$this->h.$this->file;
                if (file_exists($filename)) {
                    $this->convert = false;

                    return $filename;
                }
            }
        }
        $filename = $filebase.$this->file;
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
                lnxmcp()->header('Location: '.$this->remote_url, true);
            }
        }
        if ($this->remote_dynamic) {
            if ($this->remote_url != null) {
                lnxmcp()->header('Location: '.$this->remote_url.$this->folder.'/'.$this->file, true);
            }
        }
        if ($this->remote_url != null) {
            lnxmcp()->header('Location: '.$this->remote_url, true);
        }
        if ($this->remote_img != null) {
            lnxmcp()->header('Location: '.$this->remote_img, true);
        }
        lnxmcp()->header('HTTP/1.0 404 Not Found', true, 404);
        exit;
    }

    /**
     * callSendFile send file to  live.
     *
     * @param mixed $filename
     */
    private function callSendFile($filename)
    {
        if (file_exists($filename)) {
            $filename = realpath($filename);
            $mime = mime_content_type($filename);
            $size = filesize($filename);
            lnxmcp()->debug('file:'.$filename.' - mime:'.$mime.' - size:'.$size);
            lnxmcp()->header('Content-Type: '.$mime, false);
            lnxmcp()->header('Cache-Control: max-age='.$this->head_expire_cache, false);
            echo file_get_contents($filename);

            return;
        }
        $this->callRedirect();
    }

    /**
     * CreateBgRequest function.
     *
     * @param string $mode
     * @param string $realfile
     *
     * @return array
     */
    private function CreateBgRequest($realfile = null)
    {
        return array(
            'mode' => $this->mode,
            'action_load' => $this->action_load,
            'action_live' => $this->action_live,
            'action_batch' => $this->action_batch,
            'action_cache' => $this->action_cache,
            'action_redirect' => $this->action_redirect,
            'realfile' => $realfile,
            'convert' => $this->convert,
            'cache' => $this->remote_cache,
            'expire' => $this->head_expire_cache,
            'file' => $this->file,
            'folder' => $this->folder,
            'ext' => $this->ext,
            'tag' => $this->tag,
            'w' => $this->w,
            'h' => $this->h,
        );
    }

    /**
     * writeConvertRequest.
     */
    private function writeBgRequest($req)
    {
        lnxPutJsonFile($req, $this->file, $this->jsonpath, 'json');
    }

    /**
     * executeConvertRequest.
     */
    private function executeBgRequest($obj)
    {
        if (!is_array($obj)) {
            return;
        }
        if (isset($obj['realfile'])) {
            $filedest = $obj['realfile'];
        }
        $filesource = $this->path.'/'.$this->folder.'/'.$this->file;
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
     * run script.
     */
    private function run()
    {
        /////// DEFINE THE SITUATION
        $live = true;
        $batch = false;
        switch ($this->mode) {
            case 'background':
            case 'execute':
                $live = false;
                $batch = true;
                break;
            case 'live':
                $live = true;
                $batch = false;
                break;
            case 'all':
                $live = true;
                $batch = true;
                break;
        }
        /////// DEFINE IF IS ALLOW TO CONTROL THIS ELEMENT
        if ($this->checkAllow() == false) {
            if ($live == true) {
                $this->callRedirect();
            }

            return;
        }
        /////// INIT THE EXCHANGE OBJECT
        $obj = $this->CreateBgRequest();
        /////// CHECK IF IS PRESENT A LOADABLE FILE
        $loadfile = null;
        if ($this->action_load != null) {
            if (is_array($this->action_load)) {
                $obj = lnxMcpCmd($this->action_load, $obj);
            } else {
                $obj = lnxMcpTag($this->action_load, $obj);
            }
            if (isset($obj['return'])) {
                $obj = $obj['return'];
            }
            if (isset($obj['realfile'])) {
                $loadfile = $obj['realfile'];
            }
        } else {
            $loadfile = $this->checkFilePresent();
            $obj = $this->CreateBgRequest($loadfile);
        }
        /////// IF IS NOT FOUND GO ON CHECK CACHE AND/OR CALL REDIRECT
        if ($loadfile == null) {
            if ($live == true) {
                $this->callRedirect($obj);
            }
            if ($batch == true) {
                $this->executeBgRequest($obj);
            } else {
                $obj['mode'] = 'background';
                $this->writeBgRequest($obj);
            }

            return;
        }
        /////// IF IS FOUND AND MODE LIVE, GO ON RUN LIVE SEQUENCE
        if ($live == true) {
            if ($this->action_live != null) {
                if (is_array($this->action_live)) {
                    $obj = lnxMcpCmd($this->action_live, $obj);
                } else {
                    $obj = lnxMcpTag($this->action_live, $obj);
                }
                if (isset($obj['return'])) {
                    $obj = $obj['return'];
                }
            } else {
                $this->callSendFile($loadfile);
            }
        }
        /////// IF IS FOUND AND BATCH GO ON RUN BATCH SEQUENCE
        if ($this->convert == true) {
            if ($batch == false) {
                $obj['mode'] = 'background';
                $this->writeBgRequest($obj);
            } else {
                $this->executeBgRequest($obj);
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
            $scopein = lnxGetJsonFile($scopein, $this->jsonpath, 'json');
        }
        $this->loadScope($scopein);
        $this->run();
    }
}
