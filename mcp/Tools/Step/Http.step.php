<?php

class mcpRunHttp
{
    /**
     * extend the config based on $pathmenu array.
     *
     * @return array $scopein
     */
    public function mcpHttpPathMenuExt($pathmenu, $catmng, $scopein)
    {
        lnxmcp()->debugVar('mcpHttpPathMenuExt', 'cat.mng.', $catmng);
        $scopein = lnxmcp()->runMenu($catmng, $scopein);
        if (!is_array($pathmenu)) {
            return $scopein;
        }
        if (!isset($pathmenu[$catmng])) {
            return $scopein;
        }
        if (!is_array($pathmenu[$catmng])) {
            return lnxmcp()->runMenu($pathmenu[$catmng], $scopein);
        }
        foreach ($pathmenu[$catmng] as $mnk => $mnv) {
            $scopein[$mnk] = $mnv;
        }

        return $scopein;
    }

    /**
     * mcpPathRedirect
     * check if present on path list  and if present redirect.
     *
     * @param mixed $urlpth
     */
    public function mcpPathRedirect($urlpth)
    {
        lnxmcp()->debug('Check a Redirect Action for '.$urlpth);
        $cfgpth = lnxmcp()->getResource('path.config');
        $pathredirect = lnxGetJsonFile('PathRewrite', $cfgpth, 'json');
        if (is_array($pathredirect)) {
            if (isset($pathredirect[$urlpth])) {
                lnxmcp()->info('Found a Redirect Action for '.$urlpth);
                $redcmd = $pathredirect[$urlpth];
                if (is_array($redcmd)) {
                    foreach ($redcmd as $redhead) {
                        lnxmcp()->header($redhead, false);
                    }
                    LnxMcpExit('End Headers Redirect ');
                } else {
                    lnxmcp()->header($redcmd, true);
                }
            } else {
                $urlpart = '';
                foreach (explode('/', strtolower($urlpth)) as $urlseg) {
                    if ($urlseg != '') {
                        $urlpart .= '/'.$urlseg;
                        lnxmcp()->debug('Check a Redirect Action for partial '.$urlpart);
                        $urlcheck = $urlpart.'/*';
                        if (isset($pathredirect[$urlcheck])) {
                            lnxmcp()->info('Found a Redirect Action for partial '.$urlpart);
                            $redcmd = $pathredirect[$urlcheck];
                            if (is_array($redcmd)) {
                                foreach ($redcmd as $redhead) {
                                    lnxmcp()->header($redhead, false);
                                }
                                LnxMcpExit('End Headers Redirect ');
                            } else {
                                lnxmcp()->header($redcmd, true);
                            }
                        }
                        $urlcheck = '*/'.$urlseg.'/*';
                        if (isset($pathredirect[$urlcheck])) {
                            lnxmcp()->info('Found a Redirect Action for partial '.$urlpart);
                            $redcmd = $pathredirect[$urlcheck];
                            if (is_array($redcmd)) {
                                foreach ($redcmd as $redhead) {
                                    lnxmcp()->header($redhead, false);
                                }
                                LnxMcpExit('End Headers Redirect ');
                            } else {
                                lnxmcp()->header($redcmd, true);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * mcpAdmRedirect
     * SPECIAL PAGE AREA
     * if is /lnxmcp/tag -> run tag
     * if is /lnxmcp/module/page -> run module page.
     *
     * @param string $urlpth
     * @param array  $urlarr
     *
     * @return bool runnned;
     */
    public function mcpAdmRedirect($urlpth, $urlarr)
    {
        $res = false;
        if (lnxmcp()->getCfg('mcp.web.api')== true){
            if (substr($urlpth, 0, 10) == '/lnxmcpapi') {
                $res = true;
                lnxmcp()->Rem($_REQUEST);
                print_r(
                    lnxmcp()->runCommand($_REQUEST,$urlarr)
                );
            }
            if (lnxmcp()->getCfg('mcp.web.admin') == true) {
                if (substr($urlpth, 0, 11) == '/lnxmcpadm/') {
                    $res = true;
                    $webarg = 'home';
                    if (isset($urlarr[2])) {
                        $webarg = $urlarr[2];
                    }
                    lnxmcp()->setCommon('web.adm.cmd',$webarg);
                    lnxmcpAdm($webarg,'Httpd');
                }
            }
        }

        return $res;
    }

    /**
     * mcpRunHttp.
     */
    public function __construct()
    {
        lnxmcp()->setCfg('app.type', 'web');
        $urlpth = $_SERVER['REQUEST_URI'];
        $urlpth = str_replace('//', '/', $urlpth);
        ////// HEADER CALL
        $this->mcpPathRedirect($urlpth);
        ////// REMOVE THE ARGUMENT BLOCK
        if (stripos($urlpth, '?') != false) {
            $tmpurl = explode('?', $urlpth);
            $urlpth = $tmpurl[0];
        }
        if (empty($urlpth) or ($urlpth == '') or ($urlpth == '/')) {
            $urlpth = 'home';
        }
        lnxmcp()->setCommon('PathUrl', $urlpth);
        //// BASE DEFINITION FOR THE SCRIPT ;
        lnxmcp()->setCommon('ucm.url', lnxmcp()->getCfg('app.ucm.url'));
        lnxmcp()->setCommon('logo', lnxmcp()->getCfg('app.image.logo'));
        lnxmcp()->setCommon('icon', lnxmcp()->getCfg('app.image.icon'));
        lnxmcp()->setCommon('def', lnxmcp()->getCfg('app.def'));
        lnxmcp()->setCommon('version', lnxmcp()->getCfg('app.version'));
        $noimage = lnxmcp()->getCfg('app.ucm.noimage');
        if (empty($noimage)) {
            $noimage = '/images/no-image.gif';
        }
        lnxmcp()->setCommon('ucm.noimage', $noimage);
        $urlpth = strtolower($urlpth);
        $urlarr = explode('/', $urlpth);
        //// ADM CALL
        if ($this->mcpAdmRedirect($urlpth, $urlarr) == true) {
            return;
        }
        ////// GET BROWSER TYPE INFO
        $browser = new \LinHUniX\Mcp\Tools\browserData();
        foreach ($browser->getResult() as $bdk => $dbv) {
            if (is_array($dbv)) {
                foreach ($dbv as $sdk => $sdv) {
                    lnxmcp()->setCfg('web.'.$bdk.'.'.$sdk, $sdv);
                }
            } else {
                lnxmcp()->setCfg('web.'.$bdk, $dbv);
            }
        }
        ///// MENU CALL
        lnxmcp()->setCommon('CatUrl', $urlarr);
        $cfgpth = lnxmcp()->getResource('path.config');
        $catlist = $urlarr;
        $catcnt = sizeof($catlist);
        $scopein = $_REQUEST;
        $scopein['category'] = $urlarr;
        $pathmenu = lnxGetJsonFile('Pathmenu', $cfgpth, 'json');
        if (!is_array($pathmenu)) {
            $pathmenu = array();
        }
        $menu = 'home'.str_replace('/', '.', $urlpth);
        $lang = lnxmcp()->getCfg('web.language');
        if (empty($lang)) {
            $lang = lnxmcp()->getCfg('app.lang');
        } else {
            lnxmcp()->setCfg('app.lang', $lang);
        }
        $catlist[$catcnt++] = $lang.$menu;
        $scopein = $this->mcpHttpPathMenuExt($pathmenu, $lang, $scopein);
        $lang .= '.';
        $scopein = $this->mcpHttpPathMenuExt($pathmenu, $lang.$menu, $scopein);
        $scopein = $this->mcpHttpPathMenuExt($pathmenu, $menu, $scopein);
        if (lnxmcp()->getCfg('web.mobile') == true) {
            lnxmcp()->setCfg('app.type', 'mobile');
            $mobile = 'mobile.';
            $catlist[$catcnt++] = $mobile.$lang.$menu;
            $catlist[$catcnt++] = $mobile.$menu;
            $scopein = $this->mcpHttpPathMenuExt($pathmenu, 'mobile', $scopein);
            $scopein = $this->mcpHttpPathMenuExt($pathmenu, $mobile.$lang.$menu, $scopein);
            $scopein = $this->mcpHttpPathMenuExt($pathmenu, $mobile.$menu, $scopein);
        }
        $catlist[$catcnt++] = $menu;
        $submenu = 'main';
        $catlist[$catcnt++] = $submenu;
        $catlevel = 0;
        foreach ($urlarr as $catk => $catv) {
            if ($catv != '') {
                ++$catlevel;
                if ($catk == '') {
                    $catk = $catlevel;
                }
                $submenu .= '.'.$catv;
                $catmenu = 'cat.'.$catk.'.'.$catv;
                $catlist[$catcnt++] = $catmenu;
                $catlist[$catcnt++] = $submenu;
                $scopein = $this->mcpHttpPathMenuExt($pathmenu, $catmenu, $scopein);
                $scopein = $this->mcpHttpPathMenuExt($pathmenu, $submenu, $scopein);
            }
        }
        $scopein = $this->mcpHttpPathMenuExt($pathmenu, $urlpth, $scopein);
        if (isset($scopein['menu'])) {
            $menu = $scopein['menu'];
        }
        ksort($scopein);
        lnxmcp()->setCommon('category', $catlist);
        lnxmcp()->runMenu($menu, $scopein);
        lnxmcp()->showPage($menu, $scopein);
    }
}
