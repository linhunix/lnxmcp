<?php

namespace LinHUniX\Mcp\Provider;


use LinHUniX\Mcp\masterControlProgram;
use LinHUniX\Mcp\Model\mcpConfigArrayModelClass;
use LinHUniX\Mcp\Model\mcpServiceProviderModelClass;

class settingsProviderModel implements mcpServiceProviderModelClass {
    /**
     * Register the settings as a provider with a Pimple container
     *
     */
    public function register(masterControlProgram $mcp,mcpConfigArrayModelClass &$cfg) {
        $env=$cfg['app.env'];
        date_default_timezone_set($cfg["app.timezone"]);
        //////////////////////////////////////////////////////
        ///  READ CONFIG
        //////////////////////////////////////////////////////
        $cfgfile=$cfg['app.path'] . '/cfg/config.' .$env . '.json';
        if (file_exists ($cfgfile)){
            try {
                $cfgdata=json_decode (file_get_contents ($cfgfile));
                foreach ($cfgdata as $ck=>$cv){
                    $mcp->setCfg ($ck,$cv);
                }
            } catch (\Exception $e) {
                error_log("settingsProvider:" . $e->getMessage());
            }
        }
        $mcp->setCfg ("app.env",$env);
        if (!isset($cfg["settings"])) {
            $mcp->setCfg ("settings" , array());
        }
        //////////////////////////////////////////////////////
        ///  READ VERSION
        //////////////////////////////////////////////////////
        $verfile=$cfg['app.path'] . '/VERSION';
        if (file_exists ($verfile)){
            try {
                $mcp->setCfg ("app.ver",file_get_contents ($verfile));
            } catch (\Exception $e) {
                error_log("settingsProvider:" . $e->getMessage());
            }
        }
        date_default_timezone_set($cfg["app.timezone"]);
    }
}
