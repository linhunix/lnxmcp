<?php

namespace LinHUniX\Ln4\Service;

use LinHUniX\Mcp\Model\mcpServiceModelClass;

/**
 * LinHUniX Web Application Framework.
 *
 * @author Andrea Morello <lnxmcp@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 *
 * @version GIT:2018-v2
 */
class lnxService extends mcpServiceModelClass
{
    protected function moduleInit()
    {
        $this->spacename = __NAMESPACE__;
        $this->classname = __CLASS__;
    }
}
