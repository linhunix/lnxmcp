<?php

//// DEFINITION OF BINARY CONFIG;
$apppath = lnxmcp()->getCfg('app.path');
$cfgpath = lnxmcp()->getCfg('app.path.config');
$mcpath = lnxmcp()->getCfg('mcp.path');
$admpath = __DIR__;
$bincmd = PHP_BINARY;
$setpath = $cfgpath.'/mcp.settings.json';
$idxpath = $apppath.'/index.php';
$id2path = $apppath.'/app.php';
$cfgok = 'KO';
$appok = 'KO';
$idxok = 'KO';
$setok = 'KO';
if (is_dir($apppath)) {
    $appok = 'OK';
}
if ($apppath == '//') {
    $appok = 'KO';
}
if (is_dir($cfgpath)) {
    $cfgok = 'OK';
}
if (file_exists($setpath)) {
    $setok = 'OK';
}
if (file_exists($idxpath)) {
    $idxok = 'OK';
} elseif (file_exists($id2path)) {
    $idxok = 'OK';
    $idxpath = $id2path;
}
$cmdfile = __DIR__.'/../Cmd/'.$defcmd.'.cmd.php';
lnxmcp()->debug($cmdfile);
if (file_exists($cmdfile)) {
    echo '<!-- '.$defcmd." !-->\n";
    include $cmdfile;
} else {
    echo "<!-- DEFAULT !-->\n";
    include __DIR__.'/../Cmd/default.cmd.php';
}
