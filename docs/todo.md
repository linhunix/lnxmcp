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
* error on mcp :

    PHP Notice:  Undefined index: app.path in phar:///lnxmcp.phar/mcp/Mcp/masterControlProgram.php on line 2


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
