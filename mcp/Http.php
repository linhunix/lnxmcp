<?php
/**
 * mcpRunHttp
 *
 * @return void
 */
function mcpRunHttp(){
    $urlpth=$_SERVER["REQUEST_URI"];
    $urlpath=str_replace("//","/",$urlpath);
    $urlarr=explode("/",$urlpth);
    $cfgpth=lnxmcp()->getResource("path.config");
    ////// HEADER CALL
    $pathredirect=lnxGetJsonFile("PathRewrite",$cfgpth,"json");
    lnxmcp()->setCommon("PathUrl",$urlpth);
    lnxmcp()->setCommon("PathHeader",$pathredirect);
    if (is_array($pathredirect)){
        if (in_array($urlpth,$pathredirect)){
            $redcmd= $pathredirect[$urlpath];
            if (is_array($redcmd)){
                foreach( $redcmd as $redhead){
                    lnxmcp()->header($redhead,false);
                }
                LnxMcpExit("End Headers Redirect ");
            }else{
                lnxmcp()->header($redcmd,true);
            }
        }
    }
    ///// MENU CALL
    $catlist=$urlarr;
    $catcnt=sizeof($catlist);
    $scopein=$_REQUEST;
    $scopein["category"]=$urlarr;
    $pathmenu=lnxGetJsonFile("Pathmenu",$cfgpth,"json");
    $menu=str_replace("/",".",$urlpath);
    $catlist[$catcnt++]=$menu;
    if (!is_array($pathmenu)){
        $pathmenu=array();
    }
    $submenu="main";
    $catlist[$catcnt++]=$submenu;
    foreach($urlarr as $catk=>$catv){
        $submenu.=".".$catv;
        $catmenu="cat.".$catk.".".$catv;
        $catlist[$catcnt++]=$catmenu;
        $catlist[$catcnt++]=$submenu;
        if (in_array($catmenu,$pathmenu)){
            foreach($pathmenu[$catmenu] as $mnk=>$nuv){
                $scopein[$mnk]=$mnv;
            }
        }    
        if (in_array($submenu,$pathmenu)){
            foreach($pathmenu[$submenu] as $mnk=>$nuv){
                $scopein[$mnk]=$mnv;
            }
        }    
    }
    if (in_array($urlpth,$pathmenu)){
        foreach($pathmenu[$urlpath] as $mnk=>$nuv){
            $scopein[$mnk]=$mnv;
        }
    }
    if(isset($scopein["menu"])){
        $menu=$scopein["menu"];
    }
    ksort($scopein);
    lnxmcp()->setCommon("category",$catlist);
    lnxmcp()->runMenu($menu,$scopein);
    lnxmcp()->showPage($menu,$scopein);
}