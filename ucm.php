<?php 
/** LNXMCP-INIT **/
if (function_exists("lnxmcp") == false) {
    include $_SERVER["DOCUMENT_ROOT"] . "/app.php";
};
lnxmcp()->imhere();
/** LNXMCP-END **/
$path = lnxmcp()->getResource("path");
$filename = $path . $_SERVER["REQUEST_URI"];
if (isset($_REQUEST["file"])) {
    if (file_exists($path . $_SERVER["file"])) {
        $filename = $path . $_REQUEST["file"];
        $_SERVER["REQUEST_URI"]=$_REQUEST["file"];
    }
}
if (isset($_REQUEST["size"])) {
    if (file_exists($path . $_REQUEST["size"] . "_" . $_SERVER["REQUEST_URI"])) {
        $filename = $path . $_REQUEST["size"] . "_" . $_SERVER["REQUEST_URI"];
    }
}
if (file_exists($filename)) {
    $filename = realpath($filename);
    $mime = mime_content_type($filename);
    $size= filesize ($filename);
    lnxmcp()->debug("file:".$filename." - mime:".$mime." - size:".$size);
    switch ($mime) {
        default:
            lnxmcp()->header("Content-Type: " . $mime, false);
            echo file_get_contents($path . $_SERVER["REQUEST_URI"]);
    }
    LnxMcpExit("UCM");
}
if (isset($_REQUEST["U404"])){
    if ($_REQUEST["U404"]=="IMG"){
        $redirect_img = lnxmcp()->getResource("redirect_404_img");
        if ($redirect_img != null) {
            lnxmcp()->header("Location: " . $redirect_img , true);
        }                 
    }else{
        lnxmcp()->header("Location: " .$_REQUEST["U404"], true);
    }
}
$redirect_url = lnxmcp()->getResource("redirect_404_url");
if ($redirect_url != null) {
    lnxmcp()->header("Location: " . $redirect_url . $_SERVER["REQUEST_URI"], true);
}
lnxMcpTag("NOT-FOUND");
lnxmcp()->header("HTTP/1.0 404 Not Found", true, 404);
?>