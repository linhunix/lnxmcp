<?php
/**
 * LnxMcpCodeCompile base on opcache.
 *
 * @see app.opcache (true/false)
 *
 * @param string $file
 * @param string $bin
 *
 * @return bool status
 */
function LnxMcpCodeCompile($file)
{
    $res = false;
    if (isset($GLOBALS['scopeInit']['app.opcache'])) {
        if ($GLOBALS['scopeInit']['app.opcache'] == true) {
            $res = true;
        }
    } elseif (isset($GLOBALS['mcp'])) {
        if ($GLOBALS['mcp']->getResource('app.opcache') == true) {
            $res = true;
        }
    }
    if ($res != true) {
        return $res;
    }
    try {
        if (function_exists('opcache_is_script_cached')) {
            if (!opcache_is_script_cached($file)) {
                return opcache_compile_file($file);
            }

            return true;
        }

        return false;
    } catch (\Exception $e) {
        return false;
    }
}
