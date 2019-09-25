<?php

////////////////////////////////////////////////////////////////////////////////
// CACHE CONTROL
////////////////////////////////////////////////////////////////////////////////
/**
 * lnxCacheCtl create a cache version of the specific bloc.
 *
 * @param string $name         name of the cache object
 * @param int    $expire       expire time
 * @param array  $alt_sequence altenative sequence use to run code
 * @param array  $alt_scopein  alternative input use to run code
 *
 * @return array
 */
function lnxCacheCtl($name, $expire = 3600, $alt_sequence, $alt_scopein)
{
    $cachepath = lnxmcp()->getResource('path.cache');
    $cachedate = date('U');
    $cacheobject = lnxGetJsonFile($name, $cachepath, 'json');
    $iscacheok = false;
    $res = null;
    if (is_array($cacheobject)) {
        $expireobj = intval($cacheobject['date']) + $expire;
        if ($expireobj > $cachedate) {
            $iscacheok = true;
        }
    }
    if ($iscacheok) {
        if (isset($cacheobject['output'])) {
            echo base64_decode($cacheobject['output']);
        }
        if (isset($cacheobject['scopeOut'])) {
            $res = $cacheobject['scopeOut'];
        }

        return $res;
    }
    ob_start();
    $res = lnxmcp()->runSequence($alt_sequence, alt_scopein);
    $output = ob_get_contents();
    ob_end_clean();
    $content = array(
        'scopeOut' => $res,
        'date' => $cachedate,
    );
    if (!empty($output)) {
        $content['output'] = base64_encode($output);
        echo $output;
    }
    lnxPutJsonFile($content, $name, $cachepath, 'json');

    return $res;
}

/**
 * lnxCacheFlush the cache
 * if use filter with a specific name content.
 *
 * @param mixed $filter
 */
function lnxCacheFlush($filter)
{
    $cachepath = lnxmcp()->getResource('path.cache');
    if (!is_dir($cachepath)) {
        return true;
    }
    try {
        foreach (scandir($cachepath) as $file) {
            $isremok = false;
            if (is_file($cachepath.DIRECTORY_SEPARATOR.$file)) {
                $isremok = true;
            }
            if (!empty($filter)) {
                if (strstr($file, $filter) == false) {
                    $isremok = false;
                }
            }
            if ($isremok) {
                unlink($cachepath.DIRECTORY_SEPARATOR.$file);
            }
        }

        return true;
    } catch (\Exception $e) {
        lnxmcp()->warning('lnxCacheFlush:'.$e->getMessage());

        return false;
    }
}
function lnxCacheProcess($event, $action, $scopein = array())
{
    $mcpCacheModPath = lnxmcp()->getCfg('mcp.path').'/../mcp_modules/Wait/';
    lnxmcp()->setCfg('app.mod.path.LinHUniX.Wait', $mcpCacheModPath);
    $scopein['T'] = 'prc';
    $scopein['E'] = $event;
    $scopein['action'] = $action;
    lnxmcp()->runCommand(
        array(
            'type' => 'serviceCommon',
            'name' => 'waitprocess',
            'ispreload' => true,
            'module' => 'Wait',
            'vendor' => 'LinHUniX',
        ),
        array()
    );

    return lnxmcp()->runCommand(
        array(
            'type' => 'serviceCommon',
            'name' => 'waitprocess',
            'ispreload' => false,
            'module' => 'Wait',
            'vendor' => 'LinHUniX',
        ),
        $scopein
    );
}
