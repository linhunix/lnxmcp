<?php

///////////////////////////////////////////////////////////////////////////////////////
/// LNXMCP STANDARD INIT
///////////////////////////////////////////////////////////////////////////////////////
if (function_exists('lnxmcp') == false) {
    include $_SERVER['DOCUMENT_ROOT'].'/app.php';
}
lnxmcp()->imhere();
///////////////////////////////////////////////////////////////////////////////////////
//LNXMCP STANDARD END
///////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////
//LNXMCP CONFIG
///////////////////////////////////////////////////////////////////////////////////////
$path = lnxmcp()->getResource('path');
$allow = lnxmcp()->getResource('ucm.allow');
$convert = lnxmcp()->getResource('ucm.convert');
///////////////////////////////////////////////////////////////////////////////////////
//LNXMCP IF RESOURCE IS FILE
///////////////////////////////////////////////////////////////////////////////////////
$filename = $path.$_SERVER['REQUEST_URI'];
if (isset($_REQUEST['file'])) {
    if (file_exists($path.$_SERVER['file'])) {
        $filename = $path.$_REQUEST['file'];
        $_SERVER['REQUEST_URI'] = $_REQUEST['file'];
    }
}
///////////////////////////////////////////////////////////////////////////////////////
//LNXMCP IF RESOURCE REQUIRED SIZE
///////////////////////////////////////////////////////////////////////////////////////
if (isset($_REQUEST['h']) && isset($_REQUEST['w'])) {
    // image check value
    $iw = intval($_REQUEST['w']);
    $_REQUEST['w'] = $iw;
    $ih = intval($_REQUEST['h']);
    $_REQUEST['h'] = $ih;
    $iscorrect = false;
    // check if
    if (file_exists($path.$_REQUEST['w'].'x'.$_REQUEST['h'].'_'.$_SERVER['REQUEST_URI'])) {
        $filename = $path.$_REQUEST['w'].'x'.$_REQUEST['h'].'_'.$_SERVER['REQUEST_URI'];
        $iscorrect = true;
    }
    if ((intval($_REQUEST['w']) == 0) && (intval($_REQUEST['h']) == 0)) {
        $iscorrect = true;
    }
    if ($iscorrect == false) {
        if (intval($_REQUEST['h']) < intval($_REQUEST['w'])) {
            $_REQUEST['h'] = 0;
            if (file_exists($path.$_REQUEST['w'].'x'.$_REQUEST['h'].'_'.$_SERVER['REQUEST_URI'])) {
                $filename = $path.$_REQUEST['w'].'x'.$_REQUEST['h'].'_'.$_SERVER['REQUEST_URI'];
                $iscorrect = true;
            }
        }
    }
    if ($iscorrect == false) {
        if (intval($_REQUEST['h']) > intval($_REQUEST['w'])) {
            $_REQUEST['w'] = 0;
            if (file_exists($path.$_REQUEST['w'].'x'.$_REQUEST['h'].'_'.$_SERVER['REQUEST_URI'])) {
                $filename = $path.$_REQUEST['w'].'x'.$_REQUEST['h'].'_'.$_SERVER['REQUEST_URI'];
                $iscorrect = true;
            }
        }
    }
}
if (isset($_REQUEST['size'])) {
    if (file_exists($path.$_REQUEST['size'].'_'.$_SERVER['REQUEST_URI'])) {
        $filename = $path.$_REQUEST['size'].'_'.$_SERVER['REQUEST_URI'];
    }
}
///////////////////////////////////////////////////////////////////////////////////////
//LNXMCP IF RESOURCE IS PRESENT
///////////////////////////////////////////////////////////////////////////////////////
if (file_exists($filename)) {
    $filename = realpath($filename);
    $dirname = dirname($filename);
    $mime = mime_content_type($filename);
    $size = filesize($filename);
    lnxmcp()->debug('file:'.$filename.' - mime:'.$mime.' - size:'.$size);
    switch ($mime) {
        default:
            lnxmcp()->header('Content-Type: '.$mime, false);
            echo file_get_contents($path.$_SERVER['REQUEST_URI']);
    }
    if (isset($_REQUEST['h']) && isset($_REQUEST['w']) && $iscorrect == false) {
        if ($_REQUEST['h'] != 0) {
        }
        lnxMcpCmd(
            array(
                'type' => 'serviceCommon',
                'name' => 'gfx',
                'module' => 'Gfx',
            ),
            array(
                'T' => 'IMG',
                'effect' => 'resize',
                'source' => $path.$_SERVER['REQUEST_URI'],
                'dest' => $path.$_REQUEST['w'].'x'.$_REQUEST['h'].'_'.$_SERVER['REQUEST_URI'],
                'width' => intval($_REQUEST['w']),
                'height' => intval($_REQUEST['w']),
            )
        );
    }
    LnxMcpExit('UCM');
}
///////////////////////////////////////////////////////////////////////////////////////
//LNXMCP IF RESOURCE IS NOT PRESENT
///////////////////////////////////////////////////////////////////////////////////////
if (isset($_REQUEST['U404'])) {
    if ($_REQUEST['U404'] == 'IMG') {
        $redirect_img = lnxmcp()->getResource('redirect_404_img');
        if ($redirect_img != null) {
            lnxmcp()->header('Location: '.$redirect_img, true);
        }
    } else {
        lnxmcp()->header('Location: '.$_REQUEST['U404'], true);
    }
}
$redirect_url = lnxmcp()->getResource('redirect_404_url');
if ($redirect_url != null) {
    lnxmcp()->header('Location: '.$redirect_url.$_SERVER['REQUEST_URI'], true);
}
lnxMcpTag('NOT-FOUND');
lnxmcp()->header('HTTP/1.0 404 Not Found', true, 404);
