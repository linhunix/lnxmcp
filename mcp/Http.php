<?php

/**
 * mcpRunHttp
 *
 * @return void
 */
function mcpRunHttp()
{
    $urlpth = $_SERVER["REQUEST_URI"];
    if (empty($urlpth) or ($urlpth == "") or ($urlpth == "/")) {
        $urlpth = "home";
    }
    $browser = new \LinHUniX\Mcp\Tools\browserData();
    foreach ($browser->getResult() as $bdk => $dbv) {
        if (is_array($dbv)) {
            foreach ($dbv as $sdk => $sdv) {
                lnxmcp()->setCfg("web." . $bdk . "." . $sdk, $sdv);
            }
        } else {
            lnxmcp()->setCfg("web." . $bdk, $dbv);
        }
    }
    $urlpth = str_replace("//", "/", $urlpth);
    $urlarr = explode("/", $urlpth);
    $cfgpth = lnxmcp()->getResource("path.config");
    ////// HEADER CALL
    $pathredirect = lnxGetJsonFile("PathRewrite", $cfgpth, "json");
    lnxmcp()->setCommon("PathUrl", $urlpth);
    lnxmcp()->setCommon("CatUrl", $urlarr);
    lnxmcp()->setCommon("PathHeader", $pathredirect);
    if (is_array($pathredirect)) {
        if (in_array($urlpth, $pathredirect)) {
            $redcmd = $pathredirect[$urlpth];
            if (is_array($redcmd)) {
                foreach ($redcmd as $redhead) {
                    lnxmcp()->header($redhead, false);
                }
                LnxMcpExit("End Headers Redirect ");
            } else {
                lnxmcp()->header($redcmd, true);
            }
        }
    }
    ///// MENU CALL
    $catlist = $urlarr;
    $catcnt = sizeof($catlist);
    $scopein = $_REQUEST;
    $scopein["category"] = $urlarr;
    $pathmenu = lnxGetJsonFile("Pathmenu", $cfgpth, "json");
    $menu = "home".str_replace("/", ".", $urlpth);
    $catlist[$catcnt++] = $menu;
    if (!is_array($pathmenu)) {
        $pathmenu = array();
    }
    $submenu = "main";
    $catlist[$catcnt++] = $submenu;
    $catlevel = 0;
    foreach ($urlarr as $catk => $catv) {
        if ($catv != "") {
            $catlevel++;
            if ($catk == "") {
                $catk = $catlevel;
            }
            $submenu .= "." . $catv;
            $catmenu = "cat." . $catk . "." . $catv;
            $catlist[$catcnt++] = $catmenu;
            $catlist[$catcnt++] = $submenu;
            if (in_array($catmenu, $pathmenu)) {
                foreach ($pathmenu[$catmenu] as $mnk => $nuv) {
                    $scopein[$mnk] = $mnv;
                }
            }
            if (in_array($submenu, $pathmenu)) {
                foreach ($pathmenu[$submenu] as $mnk => $nuv) {
                    $scopein[$mnk] = $mnv;
                }
            }
        }
    }
    if (in_array($urlpth, $pathmenu)) {
        foreach ($pathmenu[$urlpath] as $mnk => $nuv) {
            $scopein[$mnk] = $mnv;
        }
    }
    if (isset($scopein["menu"])) {
        $menu = $scopein["menu"];
    }
    ksort($scopein);
    lnxmcp()->setCommon("category", $catlist);
    //// SPECIAL PAGE AREA
    if (substr($urlpth,0,8)=='/lnxmcp/'){
        if(isset($urlarr[2])){
            lnxmcp()->showCommonPage($urlarr[2]);
        }
    }
    lnxmcp()->runMenu($menu, $scopein);
    lnxmcp()->showPage($menu, $scopein);
}