<?php
/**
 * extend the config based on $pathmenu array
 * @return array $scopein
 */
function mcpHttpPathMenuExt($pathmenu,$catmng,$scopein){
    if (in_array($catmng, $pathmenu)) {
        foreach ($pathmenu[$catmng] as $mnk => $nuv) {
            $scopein[$mnk] = $mnv;
        }
    }
    return $scopein;
}


/**
 * mcpRunHttp
 *
 * @return void
 */
function mcpRunHttp()
{
    lnxmcp()->setCfg("app.type","web");
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
        if (isset($pathredirect[$urlpth])) {
            lnxmcp()-info("Found a Redirect Action for ". $urlpth);
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
    if (!is_array($pathmenu)) {
        $pathmenu = array();
    }
    $menu = "home".str_replace("/", ".", $urlpth);
    $lang= lnxmcp()->getCfg("web.language");
    if (empty($lang)){
        $lang=lnxmcp()->getCfg("app.lang");
    }else{
        lnxmcp()->setCfg("app.lang",$lang);
    }
    $catlist[$catcnt++] = $lang.$menu;
    $scopein=mcpHttpPathMenuExt($pathmenu,$lang,$scopein);
    $lang.=".";
    $scopein=mcpHttpPathMenuExt($pathmenu,$lang.$menu,$scopein);
    $scopein=mcpHttpPathMenuExt($pathmenu,$menu,$scopein);
    if (lnxmcp()->getCfg("web.mobile")==true){
        lnxmcp()->setCfg("app.type","mobile");
        $mobile="mobile.";
        $catlist[$catcnt++] = $mobile.$lang.$menu;
        $catlist[$catcnt++] = $mobile.$menu;
        $scopein=mcpHttpPathMenuExt($pathmenu,"mobile",$scopein);
        $scopein=mcpHttpPathMenuExt($pathmenu,$mobile.$lang.$menu,$scopein);
        $scopein=mcpHttpPathMenuExt($pathmenu,$mobile.$menu,$scopein);
    }
    $catlist[$catcnt++] = $menu;
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
            $scopein=mcpHttpPathMenuExt($pathmenu,$catmenu,$scopein);
            $scopein=mcpHttpPathMenuExt($pathmenu,$submenu,$scopein);
        }
    }
    $scopein=mcpHttpPathMenuExt($pathmenu,$urlpth,$scopein);
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