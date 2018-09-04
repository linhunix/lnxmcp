<?php

/**
 * LinHUniX Web Application Framework
 *
 * @author Andrea Morello <andrea.morello@freetimers.com>
 * @copyright LinHUniX Communications Ltd, 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 */

namespace LinHUniX\Pdo\Loader;

use \LinHUniX\Mcp\masterControlProgram;

class mysqlLegacyLoader {

    public function __construct(masterControlProgram $mcp, array $scopeCtl, array $scopeIn) {
        $setmysqli = false;
        if (isset($scopeIn["app.mysqli"])) {
            if ($scopeIn["app.mysqli"] == true) {
                $setmysqli = true;
            }
        }
        if ($setmysqli == true) {
            include_once "mysqlLegacyV2.php";
        } else {
            include_once "mysqlLegacyV1.php";
        }
    }

}
