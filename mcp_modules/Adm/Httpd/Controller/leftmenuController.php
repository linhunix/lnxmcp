<?php
namespace LinHUniX\LnxMcpAdmHttpd\Controller;
use LinHUniX\Mcp\Model\mcpBaseModelClass;
/**
 * LinHUniX Web Application Framework.
 *
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 *
 * @version GIT:2018-v2
 */
class leftmenuController extends mcpBaseModelClass {
    /**
     *  Ideally this method shuld be used to insert the model code and the other are to be used only as normal.
     */
    protected function moduleCore()
    {
        echo '<li><a href="/lnxmcpadm/" >Home</a></li>'.PHP_EOL;
        foreach (scandir(__DIR__) as $cfile) {
            if (strstr($cfile, 'cmd.txt') != false) {
                $ctag = explode('.', $cfile);
                echo '<li><a href="/lnxmcpadm/'.$ctag[0].'" ';
                echo ' alt="'.file_get_contents(__DIR__.'/'.$cfile).'" ';
                echo '>'.$ctag[0].'</a></li>'.PHP_EOL;
            }
        }
    }
}