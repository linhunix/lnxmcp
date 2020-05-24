<?php
/*LinHUniX Web Application Framework
*
* @author    Andrea Morello <lnxmcp@linhunix.com>
* @copyright LinHUniX L.t.d., 2018, UK
* @license   Proprietary See LICENSE.md
* @version   GIT:2018-v2
*/

namespace LinHUniX\Mcp\Component;

use LinHUniX\Mcp\masterControlProgram;

/**
* Core class for load modules
*/
final class mcpConvertClass
{
    private static $lnxmcp_cnt;
    /**
    * /////////////////////////////////////////////////////////////////////////
    * /// lnxmcp simple [tag] common scope and server (with relative dump) 
    * /////////////////////////////////////////////////////////////////////////
    */
    private static function baseconvert($text, $scopeIn = array()){
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
        return $text;
    }
    /**
     *  /////////////////////////////////////////////////////////////////////////
     *  /// lnxmcp-x tag
     *  /////////////////////////////////////////////////////////////////////////
     */
    private static function xtagconvert($text, $scopeIn = array()){
        while (stripos($text, '<lnxmcp-x-') !== false) {
            self::$lnxmcp_cnt++;
            $lp1 = stripos($text, '<lnxmcp-x-');
            $lp2 = stripos($text, ' ', $lp1);
            $lp3 = stripos($text, '>', $lp2);
            $ltagx = substr($text, ($lp1 + 1), ($lp2 - $lp1 -1));
            $lp4 = stripos($text, '</'.$ltagx.'>', $lp2);
            $lcmdx = substr($text, ($lp2 + 1 ), ($lp3 - $lp2 -1 ));
            $lblcks = substr($text, ($lp3 + 1), ($lp4 - $lp3 -1));
            $subblk = substr($text, ($lp1 ), (($lp4 - $lp1)+(strlen($ltagx)+3)) );
            $scopeCtl = array();
            $scopeInSub = $scopeIn;
            $scopeInSub['blockIn'] = $lblcks;
            $largs = explode(' ', $lcmdx);
            foreach ($largs as $ck => $cv) {
                if (strpos($cv, '=') !== false) {
                    $cvx = explode('=', $cv);
                    if ($cvx[0]!='scope-json-in'){
                        $scopeCtl[$cvx[0]] = str_replace(array('"', '\''), '', ($cvx[1]));
                    }else{
                        $scopeCtl[$cvx[0]] = str_replace('\'', '', ($cvx[1]));
                    }
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
            if (isset($scopeCtl['scope-json-in'])) {
                try {
                    $arr = json_decode($scopeCtl['scope-json-in'], true);
                    if (is_array($arr)) {
                        foreach ($arr as $ak => $av) {
                            $scopeInSub[$ak] = $av;
                        }
                    }else{
                        $scopeInSub['json-in']=$scopeCtl['scope-json-in'];
                        lnxmcp()->warning('TagConverter:scope-json-in json wrong conversion!! ');
                    }
                } catch (\Exception $e) {
                    $scopeInSub['json-in']=$scopeCtl['scope-json-in'];
                    lnxmcp()->warning('TagConverter:block-type scope-json-in '.$e->getMessage());
                }
            }
            $scopeInSub['x-tag']=$ltagx;
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
                lnxmcp()->runCommand($scopeCtl, $scopeInSub);
                $lres = ob_get_contents();
                ob_end_clean();
            } else {
                lnxmcp()->info('TagConverter:passive ');
                $lres = $scopeInSub['blockIn'];
            }
            $lret = $lres;
            $textp=$text;
            switch ($scopeCtl['block-type']) {
                case 'javascript':
                    try {
                        $jres = json_encode($lres, true);
                    } catch (\Exception $e) {
                        lnxmcp()->warning('TagConverter:block-type json error '.$e->getMessage());
                    }
                    $lret = "<script type='text/javascript' >".PHP_EOL;
                    $lret .= $lcmdx.'_value='.$jres.';'.PHP_EOL;
                    $lret .= '</script>';
                break;
                case 'print_r':
                    $lretx = print_r($lret, 1);
                    $lret=$lretx;
                break;
                case 'div-extend':
                    $lretx="\n<div id='".$ltagx."' ".$lcmdx." >\n".$lret."\n</div>\n";
                    $lret=$lretx;
                break;
                case 'style':
                    $lretx="\n<style>\n".$lret."\n</style>\n";
                    $lret=$lretx;
                break;
            }
            if ($showrem == true) {
                $lretx = "\n<!-- start-lnxmcp-x[".self::$lnxmcp_cnt.']['.$ltagx.'] '.$lcmdx." !-->\n".$lret."\n<!-- end-lnxmcp-x[".self::$lnxmcp_cnt.']['.$ltagx."] !-->\n";
                $lret=$lretx;
            }
            $text = str_ireplace($subblk, $lret, $text);
            if ($text==$textp){
                lnxMcpExit("lnxmcp-x: Conversion Tag [".$ltagx."][".$subblk."] is Corrupted!!! ");
            }
        }
        return $text;
    }
    /**
     * /////////////////////////////////////////////////////////////////////////
     * //// lnxmcp simple tag
     * /////////////////////////////////////////////////////////////////////////
     */
    private static function simpletagconvert($text, $scopeIn = array()){
        while (stripos($text, '<lnxmcp ') !== false) {
            self::$lnxmcp_cnt++;
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
                    if ($cvx[0]!='scope-json-in'){
                        $scopeCtl[$cvx[0]] = str_replace(array('"', '\''), '', ($cvx[1]));
                    }else{
                        $scopeCtl[$cvx[0]] = str_replace('\'', '', ($cvx[1]));
                    }
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
            if (isset($scopeCtl['scope-json-in'])) {
                try {
                    $arr = json_decode($scopeCtl['scope-json-in'], true);
                    if (is_array($arr)) {
                        foreach ($arr as $ak => $av) {
                            $scopeInSub[$ak] = $av;
                        }
                    }else{
                        $scopeInSub['json-in']=$scopeCtl['scope-json-in'];
                        lnxmcp()->warning('TagConverter:scope-json-in json wrong conversion!! ');
                    }
                } catch (\Exception $e) {
                    $scopeInSub['json-in']=$scopeCtl['scope-json-in'];
                    lnxmcp()->warning('TagConverter:block-type scope-json-in '.$e->getMessage());
                }
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
                lnxmcp()->runCommand($scopeCtl, $scopeInSub);
                $lres = ob_get_contents();
                ob_end_clean();
            } else {
                lnxmcp()->info('TagConverter:passive ');
                $lres = $scopeInSub['blockIn'];
            }
            $lret = $lres;
            $textp=$text;
            if ($showrem == true) {
                $lret = "\n<!-- start-lnxmcp[".self::$lnxmcp_cnt.'] '.$lcmdx." !-->\n".$lres."\n<!-- end-lnxmcp[".self::$lnxmcp_cnt."] !-->\n";
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
            if ($text==$textp){
            lnxMcpExit("lnxmcp: Conversion Tag [".$subblk."] is Corrupted!!! ");
            }

        }
        return $text;
    }
    /**
     *  /////////////////////////////////////////////////////////////////////////
     *  /// lnxmcp old style tag (compaiblity mode)
     *  /////////////////////////////////////////////////////////////////////////
     */
    private static function oldtagconvert($text, $scopeIn = array()){
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
            $textp=$text;
            ob_start();
            lnxmcp()->runTag($lcmd, $lsin);
            $lres = ob_get_contents();
            ob_end_clean();
            $text = str_ireplace('['.$lcmdx.']', $lres, $text);
        }
        if ($textp==$text){
            lnxMcpExit("[lnxmcp]:Conversion Tag [".$lcmdx."] is Corrupted!!! ");
        }
        return $text;
    }
    /**
     * /////////////////////////////////////////////////////////////////////////
     * /// Label style tag (compaiblity mode)
     * /////////////////////////////////////////////////////////////////////////
     */
    private static function labelconvert($text, $scopeIn = array(), $label = null){
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
                $textp=$text;
                ob_start();
                lnxmcp()->runTag($lcmd, $lsin);
                $lres = ob_get_clean();
                $text = str_ireplace('['.$lcmdx.']', $lres, $text);
                if ($textp==$text){
                    lnxMcpExit("Label:Conversion Tag [".$lcmdx."] is Corrupted!!! ");
                }
            }
        }
        return $text;
    }

    public static function TagConverter($text, $scopeIn = array(), $label = null)
    {
        self::$lnxmcp_cnt = 0;
        $text=self::baseconvert($text,$scopeIn);
        $text=self::xtagconvert($text,$scopeIn);
        $text=self::simpletagconvert($text,$scopeIn);
        $text=self::oldtagconvert($text,$scopeIn);
        $text=self::labelconvert($text,$scopeIn,$label);
        return $text; 
    }
}