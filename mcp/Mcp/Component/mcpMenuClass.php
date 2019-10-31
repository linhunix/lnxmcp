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

namespace LinHUniX\Mcp\Component;

/**
 * Description of mcpDebugClass.
 *
 * @author andrea
 */
class mcpMenuClass
{
    /////////////////////////////////////////////////////////////////////////////
    // ARRAY CALLER MODULE
    /////////////////////////////////////////////////////////////////////////////

    /**
     * Run a command inside $scopeCtl
     * Scopectl params are:
     * - name
     * - path
     * - ispreload
     * - modinit
     * - module
     * - vendor
     * - subcall
     * - type
     * - controllerModule
     * - blockModule
     * - ScopeInDefault
     * - ScopeInRewrite
     * - ScopeInOverwrite.
     *
     * @param mixed $scopectl
     * @param mixed $scopeIn
     *
     * @return any $ScopeOut
     */
    public static function runCommand(array $scopectl, array $scopeIn = array())
    {
        lnxmcp()->debug(print_r($scopectl, 1));
        $callname = 'none';
        if (isset($scopectl['name'])) {
            $callname = $scopectl['name'];
        }
        $path = null;
        if (isset($scopectl['path'])) {
            $path = $scopectl['path'];
        }
        $ispreload = false;
        if (isset($scopectl['ispreload'])) {
            $ispreload = $scopectl['ispreload'];
        }
        if (isset($scopectl['isPreload'])) {
            $ispreload = $scopectl['isPreload'];
        }
        if ($ispreload=='true'){
            $ispreload=true;
        }
        if ($ispreload!=true){
            $ispreload=false;
        }
        $modinit = null;
        if (isset($scopectl['modinit'])) {
            $modinit = $scopectl['modinit'];
        }
        if (isset($scopectl['module'])) {
            $modinit = $scopectl['module'];
        }
        $vendor = null;
        if (isset($scopectl['vendor'])) {
            $vendor = $scopectl['vendor'];
        }
        $subcall = null;
        if (isset($scopectl['subcall'])) {
            $subcall = $scopectl['subcall'];
        }
        $type = null;
        if (isset($scopectl['type'])) {
            $type = $scopectl['type'];
        }
        $controllerModule = null;
        if (isset($scopectl['controllerModule'])) {
            $controllerModule = $scopectl['controllerModule'];
        }
        $blockModule = null;
        if (isset($scopectl['blockModule'])) {
            $blockModule = $scopectl['blockModule'];
        }
        if (isset($scopectl['ScopeInDefault'])) {
            foreach ($scopectl['ScopeInDefault'] as $ink => $inv) {
                if (!isset($scopeIn[$ink])) {
                    $scopeIn[$ink] = $inv;
                }
            }
        }
        if (isset($scopectl['ScopeInRewrite'])) {
            foreach ($scopectl['ScopeInRewrite'] as $ink => $inv) {
                $scopeIn[$ink] = $inv;
            }
        }
        if (isset($scopectl['ScopeInOverwrite'])) {
            $scopeIn = $scopectl['ScopeInOverwrite'];
        }
        $result = null;
        lnxmcp()->info('command try to call '.$type.'>> app.'.$callname);
        switch ($type) {
            case 'exit':
                LnxMcpExit(@$scopectl['message']);
                break;
            case 'dumpexit':
                DumpAndExit(@$scopectl['message']);
                break;
            case 'print':
                print_r($scopeIn);
                break;
            case 'javascript':
                lnxmcp()->toJavascript($callname, $scopeIn);
                break;
            case 'json':
                lnxmcp()->toJson($scopeIn);
                break;
            case 'javascriptCommon':
                lnxmcp()->toJavascript('common', lnxmcp()->getCommon());
                break;
            case 'clear':
                $scopeIn = array();
                break;
            case 'header':
                $header = @$scopectl['header'];
                lnxmcp()->header($header, false);
                break;
            case 'headerClose':
                $header = @$scopectl['header'];
                lnxmcp()->header($header, true);
                break;
            case 'headerHttp':
                $header = @$scopectl['header'];
                lnxmcp()->header($header, false, false, null, true);
                break;
            case 'load':
                $result = lnxmcp()->moduleLoad($callname, $modinit, $vendor, $scopeIn);
                break;
            case 'run':
                $result = lnxmcp()->moduleRun($callname, $scopeIn);
                break;
            case 'legacy':
                $result = lnxmcp()->legacyClass($callname, $scopeIn, $modinit, $subcall, $vendor, $path);
                break;
            case 'driver':
                $result = lnxmcp()->driver($callname, $ispreload, $scopeIn, $modinit, $subcall, $vendor, $path);
                break;
            case 'query':
                $result = lnxmcp()->queryR($callname, $ispreload, $scopeIn, $modinit, $subcall, $vendor);
                break;
            case 'queryCommon':
                $result = lnxmcp()->queryCommonR($callname, $ispreload, $scopeIn, $modinit, $subcall);
                break;
            case 'queryJson':
                $result = lnxmcp()->queryJsonR($callname, $scopeIn, $modinit, $vendor, $path);
                break;
            case 'queryArray':
                $result = lnxmcp()->queryArrayR($scopeIn);
                break;
            case 'controller':
                $result = lnxmcp()->controller($callname, $ispreload, $scopeIn, $modinit, $subcall, $vendor);
                break;
            case 'controllerReturn':
                $result = lnxmcp()->controllerR($callname, $ispreload, $scopeIn, $modinit, $subcall, $vendor);
                break;
            case 'remote':
                $result = lnxmcp()->remote($callname, $scopeIn, $modinit, $subcall, $vendor);
                break;
            case 'shell':
                $result = lnxmcp()->shell($callname, $scopeIn, $modinit, $subcall, $vendor);
                break;
            case 'controllerCommon':
                $result = lnxmcp()->controllerCommon($callname, $ispreload, $scopeIn, $modinit, $subcall);
                break;
            case 'controllerCommonReturn':
                $result = lnxmcp()->controllerCommonR($callname, $ispreload, $scopeIn, $modinit, $subcall);
                break;
            case 'api':
                $result = lnxmcp()->api($callname, $ispreload, $scopeIn, $modinit, $subcall, $vendor);
                break;
            case 'apiController':
                $result = lnxmcp()->api($callname, $ispreload, $scopeIn, $modinit, $subcall, $vendor, 'Controller');
                break;
            case 'apiService':
                $result = lnxmcp()->api($callname, $ispreload, $scopeIn, $modinit, $subcall, $vendor, 'Service');
                break;
            case 'apiReturn':
                $result = lnxmcp()->apiR($callname, $ispreload, $scopeIn, $modinit, $subcall, $vendor);
                break;
            case 'apiArray':
                $result = lnxmcp()->apiA($callname, $ispreload, $scopeIn, $modinit, $subcall, $vendor);
                break;
            case 'apiCommon':
                $result = lnxmcp()->apiCommon($callname, $ispreload, $scopeIn, $modinit, $subcall);
                break;
            case 'apiArrayCommon':
                $result = lnxmcp()->apiACommon($callname, $ispreload, $scopeIn, $modinit, $subcall);
                break;
            case 'service':
                $result = lnxmcp()->service($callname, $ispreload, $scopeIn, $modinit, $subcall, $vendor);
                break;
            case 'serviceCommon':
                $result = lnxmcp()->serviceCommon($callname, $ispreload, $scopeIn, $modinit, $subcall);
                break;
            case 'serviceReturn':
                $result = lnxmcp()->serviceR($callname, $ispreload, $scopeIn, $modinit, $subcall, $vendor);
                break;
            case 'serviceCommonReturn':
                $result = lnxmcp()->serviceCommonR($callname, $ispreload, $scopeIn, $modinit, $subcall);
                break;
            case 'page':
                $result = lnxmcp()->page($callname, $scopeIn, $modinit, $vendor);
                break;
            case 'mail':
                $result = lnxmcp()->mail($callname, $scopeIn, $modinit);
                break;
            case 'render':
                $result = lnxmcp()->render($callname, $scopeIn, $modinit, $vendor);
                break;
            case 'renderCommon':
                $result = lnxmcp()->renderCommon($callname, $scopeIn, $modinit, $vendor);
                break;
            case 'block':
                $result = lnxmcp()->block($callname, $scopeIn, $modinit, $vendor);
                break;
            case 'blockCommon':
                $result = lnxmcp()->blockCommon($callname, $scopeIn, $modinit);
                break;
            case 'blockRemote':
                $result = lnxmcp()->blockRemote($callname, $scopeIn, $modinit, $vendor);
                break;
            case 'blockShell':
                $result = lnxmcp()->blockShell($callname, $scopeIn, $modinit, $vendor);
                break;
            case 'showPage':
                $result = lnxmcp()->showPage($callname, $scopeIn, $controllerModule, $blockModule);
                break;
            case 'showCommonPage':
                $result = lnxmcp()->showCommonPage($callname, $scopeIn, $controllerModule, $blockModule);
                break;
            case 'showBlock':
                $result = lnxmcp()->showBlock($callname, $scopeIn, $controllerModule, $blockModule);
                break;
            case 'showCommonBlock':
                $result = lnxmcp()->showCommonBlock($callname, $scopeIn, $controllerModule, $blockModule);
                break;
            case 'showFullCommonBlock':
                $result = lnxmcp()->showFullCommonBlock($callname, $scopeIn, $controllerModule, $blockModule);
                break;
            case 'extTemplate':
                $ext = @$scopectl['ext'];
                echo lnxMcpExtLoad($callname, $path, $ext, $scopeIn, true);
                break;
            case 'extFile':
                $ext = @$scopectl['ext'];
                echo lnxMcpExtLoad($callname, $path, $ext, $scopeIn, false);
                break;
            case 'move':
                $ext = @$scopectl['ext'];
                $default = @$scopectl['default'];
                echo  lnxmcp()->move($callname, $default, $ext, $path,true );
                break;            
            case 'goto':
                $ext = @$scopectl['ext'];
                $default = @$scopectl['default'];
                echo  lnxmcp()->move($callname, $default, $ext, $path,false );
                break;            
            case 'tag':
                echo lnxMcpTag($callname, $scopeIn);
                break;
            default:
                $result = lnxmcp()->module($callname, $path, $ispreload, $scopeIn, $modinit, $subcall, $vendor, $type);
        }
        if (isset($result['return'])) {
            if (isset($scopectl['CommonByReturn'])) {
                foreach ($scopectl['CommonByReturn'] as $ink => $inv) {
                    if (!isset($result['return'][$inv])) {
                        lnxmcp()->setCommon($ink, $result['return'][$inv]);
                    }
                }
            }
        }
        if (isset($scopectl['CommonByOut'])) {
            foreach ($scopectl['CommonByOut'] as $ink => $inv) {
                if (!isset($result[$inv])) {
                    lnxmcp()->setCommon($ink, $result[$inv]);
                }
            }
        }
        lnxmcp()->debugVar('runCommand:result>', $callname, $result);

        return $result;
    }

    /**
     * runSequence inside actions.
     *
     * @param mixed $actions
     * @param mixed $scopeIn
     *
     * @return any $ScopeOut
     */
    public static function runSequence(array $actionsSeq, $scopeIn = array())
    {
        if ($actionsSeq == null) {
            lnxmcp()->warning('sequence null!!');

            return $scopeIn;
        }
        if (isset($actionsSeq['cache']['name'])) {
            $name = $actionsSeq['cache']['name'];
            $expire = 3600;
            if (isset($actionsSeq['cache']['expire'])) {
                $expire = $actionsSeq['cache']['expire'];
            }
            unset($actionsSeq['cache']);

            return lnxCacheCtl($name, $expire, $actionsSeq, $scopeIn);
        }
        foreach ($actionsSeq as $callname => $scopeCtl) {
            lnxmcp()->info('Sequence call app.'.$callname);
            if (!isset($scopeCtl['name'])) {
                $scopeCtl['name'] = $callname;
            }
            if (isset($scopeCtl['input'])) {
                foreach ($scopeCtl['input'] as $sik => $siv) {
                    $scopeIn[$sik] = $siv;
                }
            }
            $scopeIn[$callname] = lnxmcp()->runCommand($scopeCtl, $scopeIn);
        }
        if (lnxmcp()->getCfg('mcp.debug.internal') == true) {
            lnxmcp()->debugVar('runSequence', 'res', $scopeIn);
        }

        return $scopeIn;
    }

    /**
     * Run Module as Menu sequence.
     *
     * @param string $action  name of the Doctrine
     * @param array  $scopeIn Input Array with the value need to work
     *
     * @return any $ScopeOut
     */
    public static function runMenu($action, $scopeIn = array())
    {
        $sequence = lnxmcp()->getResource('menu.'.$action);
        if ($sequence == null) {
            $seqpth = lnxmcp()->getResource('path.menus');
            if (strstr($action, '\\') != false) {
                $seqpth = lnxmcp()->getResource('path.module');
                $arcarr = explode('\\', $action);
                $action = array_pop($arcarr);
                $sqalbl = 'mod.path.'.implode('.', $arcarr);
                $sqapth = lnxmcp()->getResource($sqalbl);
                if (($sqapth != null) and ($sqapth != '')) {
                    $seqpth = $sqapth.'/mnu/';
                } else {
                    $seqpth .= '/'.implode('/', $arcarr).'/mnu/';
                }
            }
            if (lnxmcp()->getCfg('mcp.debug.internal') == true) {
                lnxmcp()->debugVar('runMenu>>path', $action, $seqpth);
            }
            if ($seqpth != null) {
                $sequence = lnxGetJsonFile($action, $seqpth, 'json');
            }
        }
        if (($sequence != null) && ($sequence != false)) {
            $ret = lnxmcp()->runSequence($sequence, $scopeIn);
            if (lnxmcp()->getCfg('mcp.debug.internal') == true) {
                lnxmcp()->debugVar('runMenu', $action, $ret);
            }

            return $ret;
        } else {
            return false;
        }
    }

    /**
     * Run Module as Tags sequence.
     *
     * @param string $action  name of the Doctrine
     * @param array  $scopeIn Input Array with the value need to work
     *
     * @return any $ScopeOut
     */
    public static function runTag($action, $scopeIn = array(), $buffer = false)
    {
        if ($buffer == true) {
            ob_start();
        }
        $ret = array();
        $sequence = lnxmcp()->getResource('tag.'.$action);
        if ($sequence == null) {
            $seqpth = lnxmcp()->getResource('path.tags');
            if (strstr($action, '\\') != false) {
                $seqpth = lnxmcp()->getResource('path.module');
                $arcarr = explode('\\', $action);
                $action = array_pop($arcarr);
                $sqalbl = 'mod.path.'.implode('.', $arcarr);
                $sqapth = lnxmcp()->getResource($sqalbl);
                if (($sqapth != null) and ($sqapth != '')) {
                    $seqpth = $sqapth.'/tag/';
                } else {
                    $seqpth .= '/'.implode('/', $arcarr).'/tag/';
                }
            }
            if (lnxmcp()->getCfg('mcp.debug.internal') == true) {
                lnxmcp()->debugVar('runTag>>path', $action, $seqpth);
            }
            if ($seqpth != null) {
                $sequence = lnxGetJsonFile($action, $seqpth, 'json');
            }
        }
        if (($sequence != null) && ($sequence != false)) {
            $ret = lnxmcp()->runSequence($sequence, $scopeIn);
        } else {
            $ret = false;
        }
        if ($buffer == true) {
            $out = ob_get_contents();
            ob_end_clean();
            if (is_array($ret)) {
                $ret['output'] = $out;
            } else {
                $res = array(
                    'return' => $ret,
                    'output' => $out,
                );
                $ret = $res;
            }
        }
        if (lnxmcp()->getCfg('mcp.debug.internal') == true) {
            lnxmcp()->debugVar('runTag', $action, $ret);
        }

        return $ret;
    }

    /**
     * Search special Tags sequence on module and run it.
     *
     * @param string $test    test to be searched
     * @param array  $scopeIn Input Array with the value need to work
     *
     * @return any $ScopeOut
     */
    public static function TagConverter($text, $scopeIn = array(), $label = null)
    {
        $lnxmcp_cnt = 0;
        $text = str_replace('[scope-dump]', print_r($scopeIn, 1), $text);
        $text = str_replace('[common-dump]', print_r(lnxmcp()->getCommon(), 1), $text);
        foreach ($scopeIn as $sink => $sinv) {
            $text = str_ireplace('[scope-'.$sink.']', $sinv, $text);
        }
        foreach (lnxmcp()->getCommon() as $comk => $comv) {
            $text = str_ireplace('[common-'.$comk.']', $comv, $text);
        }
        foreach ($_SERVER as $srvnk => $srvv) {
            $text = str_ireplace('[server-'.$srvnk.']', $srvv, $text);
        }
        while (stripos($text, '<lnxmcp ') !== false) {
            ++$lnxmcp_cnt;
            $lp1 = stripos($text, '<lnxmcp');
            $lp2 = stripos($text, '>', $lp1);
            $lp3 = stripos($text, '</lnxmcp>', $lp2);
            $lcmdx = substr($text, ($lp1 + 8), ($lp2 - $lp1 - 9));
            $lblcks = substr($text, ($lp2 + 1), ($lp3 - $lp2 - 1));
            $subblk = '<lnxmcp '.substr($text, ($lp1 + 8), ($lp3 - $lp1 - 8)).'</lnxmcp>';
            $scopeCtl = array();
            $scopeInSub = $scopeIn;
            $scopeInSub['blockIn'] = $lblcks;
            $largs = explode(' ', $lcmdx);
            foreach ($largs as $ck => $cv) {
                if (strpos($cv, '=') !== false) {
                    $cvx = explode('=', $cv);
                    $scopeCtl[$cvx[0]] = str_replace(array('"', '\''), '', ($cvx[1]));
                } else {
                    $scopeCtl[$cv] = true;
                }
            }
            $showrem = true;
            if (isset($scopeCtl['disable-rem'])) {
                $showrem = false;
            }
            if (!isset($scopeCtl['block-type'])) {
                $scopeCtl['block-type'] = '';
            }
            lnxmcp()->info('TagConverter:block-type: '.$scopeCtl['block-type']);
            lnxmcp()->debug($lblcks);
            switch ($scopeCtl['block-type']) {
                case 'json':
                    try {
                        $arr = json_decode($lblcks, true);
                        if (is_array($arr)) {
                            foreach ($arr as $ak => $av) {
                                $scopeInSub[$ak] = $av;
                            }
                        }
                    } catch (\Exception $e) {
                        lnxmcp()->warning('TagConverter:block-type json error '.$e->getMessage());
                    }
                break;
                case 'config':
                    $scopeInSub['blockIn'] = lnxmcp()->getCfg($lblcks);
                    break;
                case 'common':
                    $scopeInSub['blockIn'] = lnxmcp()->getCommon($lblcks);
                    break;
                case 'scope':
                    $scopeInSub['blockIn'] = @$scopeIn[$lblcks];
                    break;
                case 'translate':
                    if (isset($scopeCtl['block-lang'])) {
                        $lang = $scopeCtl['block-lang'];
                        $scopeInSub['blockIn'] = lnxmcp()->translateMulti($lang, $lblcks);
                    } else {
                        $scopeInSub['blockIn'] = lnxmcp()->translate($lblcks);
                    }
                    break;
                default:
                    $scopeInSub['blockIn'] = $lblcks;
            }
            $lret = '';
            if (isset($scopeCtl['type'])) {
                lnxmcp()->info('TagConverter:runcommand by type: '.$scopeCtl['type']);
                ob_start();
                self::runcommand($scopeCtl, $scopeInSub);
                $lres = ob_get_contents();
                ob_end_clean();
            } else {
                lnxmcp()->info('TagConverter:passive ');
                $lres = $scopeInSub['blockIn'];
            }
            $lret = $lres;
            if ($showrem == true) {
                $lret = "\n<!-- lnxmcp[".$lnxmcp_cnt.'] '.$lcmdx." !-->\n".$lres."\n<!-- /lnxmcp[".$lnxmcp_cnt."] !-->\n";
            }
            switch ($scopeCtl['block-type']) {
                case 'javascript':
                    try {
                        $jres = json_encode($lret, true);
                    } catch (\Exception $e) {
                        lnxmcp()->warning('TagConverter:block-type json error '.$e->getMessage());
                    }
                    $lret = "<script type='text/javascript' >".PHP_EOL;
                    $lret .= $lcmdx.'_value='.$jres.';'.PHP_EOL;
                    $lret .= '</script>';
                break;
                case 'print_r':
                    $text = str_ireplace($subblk, print_r($lret, 1), $text);
                break;
                default:
                    $text = str_ireplace($subblk, $lret, $text);
            }
        }
        while (stripos($text, '[lnxmcp-') !== false) {
            $lp1 = stripos($text, '[lnxmcp-');
            $lp2 = stripos($text, ']', $lp1);
            $lcmdx = substr($text, ($lp1 + 1), ($lp2 - $lp1 - 1));
            $largs = explode(' ', $lcmdx);
            $lcmd = $largs[0];
            $lsin = $scopeIn;
            foreach ($largs as $ck => $cv) {
                if (strpos($cv, '=') !== false) {
                    $cvx = explode('=', $cv);
                    $lsin[$cvx[0]] = $cvx[1];
                } else {
                    $lsin[$ck] = $cv;
                }
            }
            ob_start();
            self::runTag($lcmd, $lsin);
            $lres = ob_get_contents();
            ob_end_clean();
            $text = str_ireplace('['.$lcmdx.']', $lres, $text);
        }
        if ($label != null) {
            while (stripos($text, '['.$label.'-') !== false) {
                $lp1 = stripos($text, '['.$label.'-');
                $lp2 = stripos($text, ']', $lp1);
                $lcmdx = substr($text, ($lp1 + 1), ($lp2 - $lp1 - 1));
                $largs = explode(' ', $lcmdx);
                $lcmd = $largs[0];
                $lsin = $scopeIn;
                foreach ($largs as $ck => $cv) {
                    if (strpos($cv, '=') !== false) {
                        $cvx = explode('=', $cv);
                        $lsin[$cvx[0]] = $cvx[1];
                    } else {
                        $lsin[$ck] = $cv;
                    }
                }
                ob_start();
                self::runTag($lcmd, $lsin);
                $lres = ob_get_clean();
                $text = str_ireplace('['.$lcmdx.']', $lres, $text);
            }
        }

        return $text;
    }
}
