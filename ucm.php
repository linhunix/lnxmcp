<?php

ob_start();
/*LNXMCP-INIT*/
if (function_exists('lnxmcp') == false) {
    include $_SERVER['DOCUMENT_ROOT'].'/app.php';
}
lnxmcp()->imhere();
/*LNXMCP-END*/
lnxmcp()->ucm();
