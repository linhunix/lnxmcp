<?php
/*LNXMCP-INIT*/
if (function_exists("lnxmcp") == false) {
    include $_SERVER["DOCUMENT_ROOT"] . "/app.php";
};
lnxmcp()->imhere(); 
/*LNXMCP-END*/
?>
<html>
    <head>
    </head>
    <body>
    <pre>
    <?php var_dump(lnxmcp()->getCommon()); ?>
    </pre>
    </body>
</html>
