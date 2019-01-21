# test well the mail and dbase class 
# admim module 
# complete the autonomy test suite (60% done)
# cms / ln4 sub connection 
# language functinality (50% done )
# event manager (by fs )
# (x1) - calling json folder cache
# (x1) - calling sqllite env/cache
# (x1) - sql lite cache for mail and other system (promise task)
# Critical error send mail (80% done)
# todo google 
    js 
                        var rsite=encodeURI(pageurl);
                        //cm = campain manager is the solution to assign a campain 
                        var gtrackmail="<img src='https://www.google-analytics.com/collect?v=1&tid=UA-xxxxxxxx-x&t=callback&ds=web&ec=pageview&cid="+data.return+"&dl="+rsite+"' style='display:hidden !important;width:0px; height:0px;' >";
                        $("#callbackPmessage").html("<p> Thank you for your message.<br> A member of our team will contact you </p>"+gtrackmail);
# todo tag on test 
# block with css and js script 
## ISSUE
*  error on http:
[2019-01-03 14:57:40][25]:CALL DIRECT RESOURCE app.menu.InitApp=Ready
[2019-01-03 14:57:40][25]:CALL DIRECT RESOURCE app.path.menus=Ready
[2019-01-03 14:57:40][25]:lnxGetJsonFile>>file:C:\\Users\\freetimers\\PhpstormProjects\\gdc//App/mnu/\\InitApp.json and not found
[2019-01-03 14:57:40][25]:Undefined variable: urlpath[phar://C:/Users/freetimers/PhpstormProjects/gdc/mcp/lnxmcp.phar/mcp/Http.php] [2]
[2019-01-03 14:57:40][25]:CALL DIRECT RESOURCE app.path.config=Ready
[2019-01-03 14:57:40][25]:lnxGetJsonFile>>file:C:\\Users\\freetimers\\PhpstormProjects\\gdc//cfg/\\PathRewrite.json and not found
[2019-01-03 14:57:40][25]:lnxGetJsonFile>>file:C:\\Users\\freetimers\\PhpstormProjects\\gdc//cfg/\\Pathmenu.json and not found
[2019-01-03 14:57:40][25]:CALL DIRECT RESOURCE app.menu.=Null
[2019-01-03 14:57:40][25]:CALL DIRECT RESOURCE app.path.menus=Ready
[2019-01-03 14:57:40][25]:lnxGetJsonFile>>file:C:\\Users\\freetimers\\PhpstormProjects\\gdc//App/mnu/\\.json and not found
[2019-01-03 14:57:40][25]:MCP>>showPage>>
[2019-01-03 14:57:40][25]:MCP>>Gdc>>controller>>
[2019-01-03 14:57:40][1]:WorkingArea:statmentModule
[2019-01-03 14:57:40][1]:WorkingArea:moduleCaller
[2019-01-03 14:57:40][1]:WorkingArea:initModule
[2019-01-03 14:57:40][1]:app.Controller.
[2019-01-03 14:57:40][1]:WorkingArea:loadModule
[2019-01-03 14:57:40][1]:status:[]\\Gdc\\\\Controller\\Controller IS NOT PRESENT - NEED TO BE LOAD
[2019-01-03 14:57:40][1]:status:[]C:\\Users\\freetimers\\PhpstormProjects\\gdc//App/mod/Gdc//autoload.php file not exist!
[2019-01-03 14:57:40][1]:status:[]C:\\Users\\freetimers\\PhpstormProjects\\gdc//App/mod//Controller/Controller.php file not exist!
[2019-01-03 14:57:40][1]:status:[]\\Gdc\\\\Controller\\Controller IS NOT PRESENT - NEED TO BE LOAD
[2019-01-03 14:57:40][1]:WorkingArea:initModule
[2019-01-03 14:57:40][1]:status:[]Gdc\\\\Controller\\Controller not a class
[2019-01-03 14:57:40][1]:status:[1]app.Controller. ScopeOut is set!
[2019-01-03 14:57:40][1]:status:[1]app.Controller. OK DONE
[2019-01-03 14:57:40][1]:status:[]moduleCaller has no data
[2019-01-03 14:57:40][1]:R:5
[2019-01-03 14:57:40][25]:MCP>>Gdc>>page>>
[2019-01-03 14:57:40][1]:WorkingArea:statmentModule
[2019-01-03 14:57:40][1]:WorkingArea:moduleLoader
[2019-01-03 14:57:40][1]:WorkingArea:loadModule
[2019-01-03 14:57:40][1]:status:[]\\Gdc\\\\Page\\Page IS NOT PRESENT - NEED TO BE LOAD
[2019-01-03 14:57:40][1]:status:[]C:\\Users\\freetimers\\PhpstormProjects\\gdc//App/tpl//Gdc//autoload.php file not exist!
[2019-01-03 14:57:40][1]:status:[]C:\\Users\\freetimers\\PhpstormProjects\\gdc//App/tpl///Page/Page.inc.php file not exist!
[2019-01-03 14:57:40][1]:status:[]\\Gdc\\\\Page\\Page IS NOT PRESENT - NEED TO BE LOAD
[2019-01-03 14:57:40][25]:CALL DIRECT RESOURCE app.debug=Ready

## get screen size 
jquery:

$(function() {
    $.post('some_script.php', { width: screen.width, height:screen.height }, function(json) {
        if(json.outcome == 'success') {
            // do something with the knowledge possibly?
        } else {
            alert('Unable to let PHP know what the screen resolution is!');
        }
    },'json');
});

PHP (some_script.php)

<?php
// For instance, you can do something like this:
if(isset($_POST['width']) && isset($_POST['height'])) {
    $_SESSION['screen_width'] = $_POST['width'];
    $_SESSION['screen_height'] = $_POST['height'];
    echo json_encode(array('outcome'=>'success'));
} else {
    echo json_encode(array('outcome'=>'error','error'=>"Couldn't save dimension info"));
}
?>
## check mobile 
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}