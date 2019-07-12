<?php
/**
 * LnxMcpCodeCompile base on opcache.
 *
 * @param string $file
 * @param string $bin
 *
 * @return bool status
 */
function LnxMcpCodeCompile($file)
{
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
