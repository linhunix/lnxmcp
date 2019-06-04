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
final class UniversalContentManager
{
    /////////////////////////////////////////////////////////////////////////////////////
    /// VARIABLES
    /////////////////////////////////////////////////////////////////////////////////////
    private $file;
    private $w;
    private $h;
    private $tag_pre;
    private $tag_post;
    private $ranges;
    private $ext;
    private $base;
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
    private $minsizelimit;

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
        $this->minsizelimit = lnxmcp()->getResource('ucm.minsizelimit');
        if ($this->minsizelimit == null) {
            $this->minsizelimit = 50;
        }
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
            $this->head_expire_cache = '3600';
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
        lnxmcp()->debugVar('ucm loadScope', 'SCOPEIN', $scopein);
        $this->w = 0;
        $this->h = 0;
        $this->file = @$scopein['REQUEST_URI'];
        if (isset($scopein['file'])) {
            $this->file = $scopein['file'];
        }
        if (strstr($this->file, '?') != false) {
            $farr = explode('?', $this->file);
            $this->file = $farr[0];
        }
        $obj = lnxGetJsonFile($this->file, $this->path, 'json');
        if (is_array($obj)) {
            foreach ($obj as $ok => $ov) {
                $scopein[$ok] = $ov;
            }
        }
        lnxmcp()->debugVar('ucm loadScope', 'file', $this->file);
        $this->tag_pre = '';
        if (isset($scopein['tag'])) {
            $this->tag_post = $scopein['tag'];
        } elseif (isset($scopein['size'])) {
            $this->tag_post = $scopein['size'];
        }
        if (isset($scopein['tag_post'])) {
            $this->tag_post = $scopein['tag_post'];
        }
        if (isset($scopein['tag_pre'])) {
            $this->tag_pre = $scopein['tag_pre'];
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
        $earr = explode('.', $this->file);
        $this->ext = array_pop($earr);
        $this->base = implode('.', $earr);
        unset($earr);
        if (isset($scopein['ext'])) {
            $this->ext = $scopein['ext'];
        }
        if (isset($scopein['U404'])) {
            $this->remote_url = $scopein['U404'];
        }
        $this->mode = 'live';
        if (isset($scopein['mode'])) {
            $this->mode = $scopein['mode'];
        }
        if (isset($scopein['expire'])) {
            $this->head_expire_cache = $scopein['expire'];
        }
    }

    /**
     *  loadAllow ref.
     *
     * @param array $allowcfg
     */
    private function LoadAllow($allowcfg)
    {
        lnxmcp()->debugVar('ucm', 'loadAllow', $allowcfg);
        if (isset($allowcfg['folder'])) {
            $this->folder = $allowcfg['folder'];
        }
        if (isset($allowcfg['path'])) {
            $this->path = $allowcfg['path'];
        }
        if (isset($allowcfg['tag'])) {
            $this->tag_post = $allowcfg['tag'];
        }
        if (isset($allowcfg['tag_post'])) {
            $this->tag_post = $allowcfg['tag_post'];
        }
        if (isset($allowcfg['tag_pre'])) {
            $this->tag_pre = $allowcfg['tag_pre'];
        }
        if (isset($allowcfg['dynamic'])) {
            $this->remote_dynamic = $allowcfg['dynamic'];
        }
        if (isset($allowcfg['redirect_404_url'])) {
            $this->remote_url = $allowcfg['redirect_404_url'];
        }
        if (isset($allowcfg['redirect_404_img'])) {
            $this->remote_img = $allowcfg['redirect_404_img'];
        }
        if (isset($allowcfg['ranges'])) {
            $this->ranges = $allowcfg['ranges'];
        }
        if (isset($allowcfg['expire'])) {
            $this->head_expire_cache = $allowcfg['expire'];
        }
        if (isset($allowcfg['minsizelimit'])) {
            $this->minsizelimit = $allowcfg['minsizelimit'];
        }
        if (isset($allowcfg['convert'])) {
            $this->convert = $allowcfg['convert'];
        }
        if (isset($allowcfg['cache'])) {
            $this->remote_cache = $allowcfg['cache'];
        }
        if (isset($allowcfg['action_load'])) {
            $this->action_load = $allowcfg['action_load'];
        }
        if (isset($allowcfg['action_live'])) {
            $this->action_live = $allowcfg['action_live'];
        }
        if (isset($allowcfg['action_batch'])) {
            $this->action_batch = $allowcfg['action_batch'];
        }
        if (isset($allowcfg['action_cache'])) {
            $this->action_cache = $allowcfg['action_cache'];
        }
        if (isset($allowcfg['action_redirect'])) {
            $this->action_redirect = $allowcfg['action_redirect'];
        }
    }

    /**
     * isallow function check if is allow or not a specific extension.
     *
     * @return bool
     */
    private function checkAllow()
    {
        lnxmcp()->debug('ucm allow array:try');
        if (is_array($this->allow)) {
            lnxmcp()->debug('ucm allow array:true');
            $folderext = $this->folder.'/*.'.$this->ext;
            $folderext = str_replace('//', '/', $folderext);
            lnxmcp()->debugVar('ucm allow', 'try search', $folderext);
            if (isset($this->allow[$folderext])) {
                if (is_array($this->allow[$folderext])) {
                    $this->LoadAllow($this->allow[$folderext]);
                }
            } elseif (isset($this->allow[$this->folder])) {
                lnxmcp()->debugVar('ucm allow', 'search found', $this->folder);
                if (is_array($this->allow[$this->folder])) {
                    $this->LoadAllow($this->allow[$this->folder]);
                }
            } elseif (isset($this->allow[$this->ext])) {
                lnxmcp()->debugVar('ucm allow', 'search found', $this->ext);
                if (is_array($this->allow[$this->ext])) {
                    $this->LoadAllow($this->allow[$this->ext]);
                }
            } elseif (isset($this->allow['default'])) {
                lnxmcp()->debugVar('ucm allow', 'search found', 'default');
                if (is_array($this->allow['default'])) {
                    $this->LoadAllow($this->allow['default']);
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
     * checkFilePresentBySize check file by size.
     *
     * @param mixed wsize
     * @param mixed hsize
     *
     * @return string filename
     */
    private function checkFilePresentBySize($wsize, $hsize)
    {
        if (($wsize < $this->minsizelimit) && (($hsize < $this->minsizelimit))) {
            $this->convert = false;

            return null;
        }
        $filebase = $this->path.'/'.$this->folder;
        $filename = $filebase.$wsize.'x'.$hsize.'_'.$this->file;
        lnxmcp()->debugVar('ucm', ' try if exist', $filename);
        if (file_exists($filename)) {
            $this->convert = false;

            return $filename;
        }
        $filename = $filebase.$this->base.'_'.$wsize.'x'.$hsize.'.'.$this->ext;
        lnxmcp()->debugVar('ucm', ' try if exist', $filename);
        if (file_exists($filename)) {
            $this->convert = false;

            return $filename;
        }

        return null;
    }

    /**
     * checkFilePresentByTag check file by tag.
     *
     * @param string tpre
     * @param string tpost
     *
     * @return string filename
     */
    private function checkFilePresentByTag($tpre, $tpost)
    {
        $filebase = $this->path.'/'.$this->folder;
        if (empty($tpre) and empty($tpost)) {
            return null;
        }
        $filename = $filebase.$tpre.$this->base.$tpost.'.'.$this->ext;
        lnxmcp()->debugVar('ucm', ' try if exist', $filename);
        // check if file exitst
        if (file_exists($filename)) {
            $this->convert = false;

            return $filename;
        }
        if (!empty($tpost)) {
            $filename = $filebase.$this->base.$tpost.'.'.$this->ext;
            lnxmcp()->debugVar('ucm', ' try if exist', $filename);
            // check if file exitst
            if (file_exists($filename)) {
                $this->convert = false;

                return $filename;
            }
        }
        if (!empty($tpre)) {
            $filename = $filebase.$tpre.$this->file;
            lnxmcp()->debugVar('ucm', ' try if exist', $filename);
            // check if file exitst
            if (file_exists($filename)) {
                $this->convert = false;

                return $filename;
            }
        }

        return null;
    }

    /**
     * checkFilePresentByRanges function check if exist local the file as part of ranges.
     * tag+filename+tag.ext
     * filename+tag.ext
     * tag+filename.ext
     * wxh_filename.ext
     * filename_wxh.ext.
     *
     * @return string filename
     */
    private function checkFilePresentByRanges()
    {
        $trysimmetric = true;
        if ($this->convert == true) {
            if (is_array($this->ranges)) {
                foreach ($this->ranges as $rtag => $rvalue) {
                    $rangefound = false;
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
                            $rangefound = true;
                            $rarr = explode('x', $rtag);
                            $this->w = $rarr[0];
                            $this->h = $rarr[1];
                        }
                    } else {
                        if (!isset($rvalue['max'])) {
                            $rvalue['max'] = 999999999;
                        }
                        $rmax = intval($rvalue['max']);
                        if (!isset($rvalue['min'])) {
                            $rvalue['min'] = 0;
                        }
                        $rmin = intval($rvalue['min']);
                        if (!isset($rvalue['range'])) {
                            $rvalue['range'] = 'w';
                        }
                        lnxmcp()->debugVar('ucm ranges try', 'range', $rvalue['range']);
                        lnxmcp()->debugVar('ucm ranges try', 'max', $rvalue['max']);
                        lnxmcp()->debugVar('ucm ranges try', 'min', $rvalue['min']);
                        if ($rvalue['range'] == 'h') {
                            if (($this->h >= $rmin) && ($this->h <= $rmax)) {
                                $rangefound = true;
                                $this->w = 0;
                            }
                        } else {
                            if (($this->w >= $rmin) && ($this->w <= $rmax)) {
                                $rangefound = true;
                                $this->h = 0;
                            }
                        }
                    }
                    if ($rangefound == true) {
                        lnxmcp()->debugVar('ucm ranges found', $rtag, $rvalue);
                        if (isset($rvalue['h'])) {
                            $this->h = $rvalue['h'];
                        }
                        if (isset($rvalue['w'])) {
                            $this->w = $rvalue['w'];
                        }
                        if (isset($rvalue['s'])) {
                            $trysimmetric = $rvalue['s'];
                        }
                        if (isset($rvalue['taglr'])) {
                            switch ($rvalue['taglr']) {
                                case 'l':
                                    $this->tag_pre = $rtag;
                                    break;
                                case 'r':
                                    $this->tag_post = $rtag;
                                    break;
                                case 'fl':
                                    $this->file = $rtag.$this->file;
                                    break;
                                case 'fr':
                                    $this->file = $this->base.$rtag.'.'.$this->ext;
                                    break;
                                case 'e':
                                    $this->ext = $rtag;
                                    break;
                                case 'fe':
                                    $this->file = $this->base.'.'.$rtag;
                                    $this->ext = $rtag;
                                    break;
                                case 'f':
                                    $this->file = $rtag.'.'.$this->ext;
                                    $this->base = $rtag;
                                    break;
                            }
                        }
                    }
                }
            }
        }
        lnxmcp()->debugVar('ucm ranges set', 'base', $this->base);
        lnxmcp()->debugVar('ucm ranges set', 'ext', $this->ext);
        lnxmcp()->debugVar('ucm ranges set', 'file', $this->file);
        lnxmcp()->debugVar('ucm ranges set', 'tag_pre', $this->tag_pre);
        lnxmcp()->debugVar('ucm ranges set', 'tag_post', $this->tag_post);
        lnxmcp()->debugVar('ucm ranges set', 'h', $this->w);
        lnxmcp()->debugVar('ucm ranges set', 'w', $this->h);
        $filename = $this->checkFilePresentByTag($this->tag_pre, $this->tag_post);
        if ($filename != null) {
            return $filename;
        }
        $filename = $this->checkFilePresentBySize($this->w, $this->h);
        if ($filename != null) {
            return $filename;
        }
        if ($trysimmetric == true) {
            if ($this->convert == true) {
                if ($this->w < $this->h) {
                    if ($this->h < $this->minsizelimit) {
                        $this->h = $this->minsizelimit;
                    }
                    $this->w = 0;
                    $filename = $this->checkFilePresentBySize($this->w, $this->h);
                    if ($filename != null) {
                        return $filename;
                    }
                }
            }
            if ($this->convert == true) {
                if ($this->w > $this->h) {
                    if ($this->w < $this->minsizelimit) {
                        $this->w = $this->minsizelimit;
                    }
                    $this->h = 0;
                    $filename = $this->checkFilePresentBySize($this->w, $this->h);
                    if ($filename != null) {
                        return $filename;
                    }
                }
            }
        }

        return null;
    }

    /**
     * checkFilePresent function check if exist local the file.
     * tag+filename+tag.ext
     * filename+tag.ext
     * tag+filename.ext
     * wxh_filename.ext
     * filename_wxh.ext.
     *
     * @return string filename
     */
    private function checkFilePresent()
    {
        if (($this->w === 0) && ($this->h === 0)) {
            $this->convert = false;
        }
        lnxmcp()->debug('ucm >> checkFilePresentByTag');
        $filename = $this->checkFilePresentByTag($this->tag_pre, $this->tag_post);
        if ($filename != null) {
            return $filename;
        }
        lnxmcp()->debug('ucm >> checkFilePresentBySize');
        $filename = $this->checkFilePresentBySize($this->w, $this->h);
        if ($filename != null) {
            return $filename;
        }
        lnxmcp()->debug('ucm >> checkFilePresentByRanges');
        $filename = $this->checkFilePresentByRanges();
        if ($filename != null) {
            return $filename;
        }
        $filebase = $this->path.'/'.$this->folder;
        $filename = $filebase.$this->file;
        lnxmcp()->debugVar('ucm', ' try if exist', $filename);
        if (file_exists($filename)) {
            return $filename;
        }

        return  null;
    }

    /**
     * callRedirect function.
     * manage the redirect funcion.
     */
    private function callRedirect()
    {
        lnxmcp()->debug('ucm action callRedirect');
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
        lnxmcp()->debug('ucm action callSendFile');
        if (file_exists($filename)) {
            $filename = realpath($filename);
            $mime = lnxMcpMimeFile($filename);
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
        lnxmcp()->debug('ucm action CreateBgRequest');
        if ($realfile == null) {
            lnxmcp()->debug('ucm action CreateBgRequest > generate realfile');
            $realfile = $this->path.'/'.$this->folder;
            if (!empty($this->tag_pre)) {
                lnxmcp()->debugVar('ucm', 'action add tag_pre', $this->tag_pre);
                $realfile .= $this->tag_pre;
            }
            $realfile .= $this->base;
            if (!empty($this->tag_post)) {
                lnxmcp()->debugVar('ucm', 'action add tag_post', $this->tag_post);
                $realfile .= $this->tag_post;
            } elseif (($this->w != 0) || ($this->h != 0)) {
                if (($this->w != 0) && ($this->h < $this->minsizelimit)) {
                    $this->h = $this->minsizelimit;
                }
                if (($this->w != 0) && ($this->w < $this->minsizelimit)) {
                    $this->w = $this->minsizelimit;
                }
                $size_tag = '_'.$this->w.'x'.$this->h;
                lnxmcp()->debugVar('ucm', 'action add size', $size_tag);
                $realfile .= $size_tag;
            }
            $realfile .= '.'.$this->ext;
        }
        lnxmcp()->debugVar('ucm', 'obj realfile', $realfile);

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
            'tag_pre' => $this->tag_pre,
            'tag_post' => $this->tag_post,
            'w' => $this->w,
            'h' => $this->h,
        );
    }

    /**
     * writeConvertRequest.
     */
    private function writeBgRequest($req)
    {
        lnxmcp()->debug('ucm action writeBgRequest');
        lnxPutJsonFile($req, $this->file, $this->jsonpath, 'json');
    }

    /**
     * executeConvertRequest.
     */
    private function executeBgRequest($obj)
    {
        lnxmcp()->debug('ucm action executeBgRequest');
        if (!is_array($obj)) {
            return;
        }
        if (isset($obj['realfile'])) {
            $filedest = $obj['realfile'];
        }
        if ($filedest == null) {
            return;
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
        lnxDelJsonFile($this->file, $this->jsonpath, 'json');
    }

    /**
     * run script.
     */
    private function run()
    {
        /////// DEFINE THE SITUATION
        lnxmcp()->debugVar('ucm', ' mode', $this->mode);
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
        lnxmcp()->debugVar('ucm', ' live', $live);
        lnxmcp()->debugVar('ucm', ' batch', $batch);
        if ($this->checkAllow() == false) {
            lnxmcp()->debug('ucm check allow false');
            if ($live == true) {
                $this->callRedirect();
            }

            return;
        }
        lnxmcp()->debug('ucm check allow true');
        /////// INIT THE EXCHANGE OBJECT
        $obj = $this->CreateBgRequest();
        /////// CHECK IF IS PRESENT A LOADABLE FILE
        $loadfile = null;
        if ($this->action_load != null) {
            lnxmcp()->debug('ucm action load');
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
            lnxmcp()->debug('ucm action checkFilePresent');
            $loadfile = $this->checkFilePresent();
            $obj = $this->CreateBgRequest($loadfile);
        }
        lnxmcp()->debugVar('ucm', ' loadfile', $loadfile);
        /////// IF IS NOT FOUND GO ON CHECK CACHE AND/OR CALL REDIRECT
        if ($loadfile == null) {
            lnxmcp()->debug('ucm file not present');
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
        lnxmcp()->debug('ucm file present');
        /////// IF IS FOUND AND MODE LIVE, GO ON RUN LIVE SEQUENCE
        if ($live == true) {
            if ($this->action_live != null) {
                lnxmcp()->debug('ucm action live');
                if (is_array($this->action_live)) {
                    $obj = lnxMcpCmd($this->action_live, $obj);
                } else {
                    $obj = lnxMcpTag($this->action_live, $obj);
                }
                if (isset($obj['return'])) {
                    $obj = $obj['return'];
                }
            } else {
                lnxmcp()->debug('ucm action callSendFile');
                $this->callSendFile($loadfile);
            }
        }
        /////// IF IS FOUND AND BATCH GO ON RUN BATCH SEQUENCE
        if ($this->convert == true) {
            $obj = $this->CreateBgRequest(null);
            lnxmcp()->debug('ucm action convert');
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
     * @param array $scopein
     */
    public function __construct($scopein = null)
    {
        lnxmcp()->debug('ucm load Cfg');
        $this->loadCfg();
        lnxmcp()->debug('ucm load ScopeIn');
        if ($scopein == null) {
            $scopein = $_REQUEST;
            $scopein['REQUEST_URI'] = $_SERVER['REQUEST_URI'];
            lnxmcp()->debug('ucm load request');
        }
        if (!is_array($scopein)) {
            $scopein = lnxGetJsonFile($scopein, $this->jsonpath, 'json');
            lnxmcp()->debug('ucm try to load scopein' + $scopein);
        } else {
            if (isset($scopein['ucmjob'])) {
                $ucmjob = $scopein['ucmjob'];
                $ucmjob = stripslashes($ucmjob);
                $scopein = lnxGetJsonFile($ucmjob, $this->jsonpath, 'json');
            }
        }

        lnxmcp()->debug('ucm load Scope');
        $this->loadScope($scopein);
        lnxmcp()->debug('ucm RUN');
        $this->run();
    }
}
