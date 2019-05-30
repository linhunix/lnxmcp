<?php

/*LNXMCP-INIT*/
if (function_exists('lnxmcp') == false) {
    include $_SERVER['DOCUMENT_ROOT'].'/src.php';
}
lnxmcp()->imhere();
/*LNXMCP-END*/
new \LinHUniX\Mcp\Tools\UniversalContentManager();
