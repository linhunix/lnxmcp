<?php

/**
 * LinHUniX Web Application Framework
 *
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 */

namespace LinHUniX\Mcp\Model;

use LinHUniX\Mcp\masterControlProgram;



/**
 * @see Head.php caller of the config
 * @see ftSimpleMCP Master Control Program
 */


class mcpRemoteObjectModelClass extends mcpBaseModelClass
{
    function moduleCore()
    {
        try{
            if (!isset($this->argIn["url"])){
                return false;
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $$this->argIn["url"]);
            if (isset($this->argCtl["proxy"])){
                curl_setopt($ch, CURLOPT_PROXY, $this->argIn["proxy"]);
                if (isset($this->argCtl["proxyUser"])){
                    $proxyauth=$this->argCtl["proxyUser"].":".@$this->argCtl["proxyPass"];
                    curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);   // Use if proxy have username and password
                }
            }
            curl_setopt($ch, CURLOPT_HEADER, 0); // return headers 0 no 1 yes
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return page 1:yes
            curl_setopt($ch, CURLOPT_TIMEOUT, 200); // http request timeout 20 seconds
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects, need this if the url changes
            curl_setopt($ch, CURLOPT_MAXREDIRS, 2); //if http server gives redirection responce
            curl_setopt($ch, CURLOPT_USERAGENT,
                "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
            if (isset($this->argCtl["cookiesfile"])){
                curl_setopt($ch, CURLOPT_COOKIEJAR, $this->argCtl["cookiesFile"]); // cookies storage / here the changes have been made
                curl_setopt($ch, CURLOPT_COOKIEFILE, $this->argCtl["cookiesFile"]);
            }
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // false for https
            curl_setopt($ch, CURLOPT_ENCODING, "gzip"); // the page encoding
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            $response = curl_exec($ch);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $this->argOut["header"] = substr($response, 0, $header_size);
            $this->argOut["return"] = substr($response, $header_size);
            curl_close($ch); // close the connection
        }catch(\Exception $e){
            $this->getMcp()->warning($e->getMessage());
            return false;
        }
        return true;  
    }
}