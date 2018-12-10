<?php
/*LNXMCP-INIT*/
putenv("MCP_MODE=TEST");
if (function_exists("lnxmcp") == false) {
    include $_SERVER["DOCUMENT_ROOT"] . "/app.php";
};
lnxmcp()->imhere(); 
/*LNXMCP-END*/
if (!isset($_REQUEST["chk"])) {
    $_REQUEST["chk"] = "web/test";
}
?>
<html>
    <head>
    </head>
    <body>

    <HR>
    <h1>Common</h1>
    <pre>
    <?php var_dump(lnxmcp()->getCommon()); ?>
    </pre>
    <h1>Cfg</h1>
    <pre>
    <?php var_dump(lnxmcp()->getCfg()); ?>
    </pre>
    <h1>Check <?= $_REQUEST["chk"]; ?></h1>
    <pre>
    <? lnxmcpChk($_REQUEST["chk"]) ?>
    </pre>
    </body>
</html>
