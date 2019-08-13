<?php
/**
 * CLASS AREA INDEX.
 */
// AUTO LOAD BASIC FUNCTION
require_once __DIR__.'/Class/Load.class.php';
/// AUTO RUN
$mcp_autoload = new mcpAutoload($mcp_path.'/Mcp');
$mcp_autoload = new mcpAutoload($mcp_path.'/Pdo');
$mcp_autoload = new mcpAutoload($mcp_path.'/Mail');
$mcp_autoload = new mcpAutoload($mcp_path.'/Gfx');
$mcp_autoload = new mcpAutoload($mcp_path.'/Ln4');
$mcp_autoload = new mcpAutoload($mcp_path.'/Auth');
