<?php
//// DEFINITION OF BINARY CONFIG;
$apppath = lnxmcp()->getCfg("app.path");
$cfgpath = lnxmcp()->getCfg("app.path.config");
$mcpath = lnxmcp()->getCfg("mcp.path");
$admpath = __DIR__;
$bincmd  = PHP_BINARY;
$setpath=$cfgpath."/mcp.settings.json";
$idxpath=$apppath."/index.php";
$id2path=$apppath."/app.php";
$cfgok="KO";
$appok="KO";
$idxok="KO";
$setok="KO";
if (is_dir($apppath)){
    $appok="OK";
}
if ($apppath=="//") {
    $appok="KO";
}
if (is_dir($cfgpath)){
    $cfgok="OK";
}
if (file_exists($setpath)) {
    $setok="OK";
}
if (file_exists($idxpath)) {
    $idxok="OK";
}elseif (file_exists($id2path)) {
    $idxok="OK";
    $idxpath=$id2path;
}


echo "//////////////////////////////////////////////////////////".PHP_EOL;
echo "|-> PATH APP:".$apppath."(".$appok.")".PHP_EOL;
echo "|-> PATH IDX:".$idxpath."(".$idxok.")".PHP_EOL;
echo "|-> PATH CFG:".$cfgpath."(".$cfgok.")".PHP_EOL;
echo "|-> PATH STS:".$setpath."(".$setok.")".PHP_EOL;
echo "|-> PATH MCP:".$mcpath.PHP_EOL;
echo "|-> PATH ADM:".$admpath.PHP_EOL;
echo "|-> PATH PHP:".$bincmd.PHP_EOL;
echo "|-> VERS PHP:".PHP_VERSION.PHP_EOL;
echo "|-> VERS SYS:".PHP_OS.PHP_EOL;
echo "|-> NAME SYS:".$_SERVER["HOSTNAME"].PHP_EOL;
echo "//////////////////////////////////////////////////////////".PHP_EOL;
