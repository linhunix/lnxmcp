<?php
function mcpRunHttp(){
    $urlpth=$_SERVER["REQUEST_URI"];
    $urlarr=explode("/",$urlpth);
    $cfgpth=lnxmcp()->getResource("path.config");
    $pathredirect=lnxGetJsonFile("PathRewrite",$cfgpth,"json");
    if (is_array($pathredirect)){
        if (in_array($urlpth,$pathredirect)){
            $redcmd= $pathredirect[$urlpath];
            lnxmcp()->header($redcmd,true);
        }
    }
    $pathmenu=lnxGetJsonFile("Pathmenu",$cfgpth,"json");
    if (is_array($pathmenu)){
        $scopein=$_REQUEST;
        $scopein["category"]=$urlarr;
        $menu=str_replace("/",".",$urlpath);
        $submenu="main";
        foreach($scopein["category"] as $catk=>$catv){
            $submenu.=".".$catv;
            $catmenu="cat.".$catk.".".$catv;
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
        lnxmcp()->runMenu($menu,$scopein);
    }
}