<?php

/**
 * LinHUniX Web Application Framework
 *
 * @author Andrea Morello <lnxmcp@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 */

namespace LinHUniX\Chk\Controller;
lnxmcpUse('LinHUniX\Chk\model\mcpCheckModelClass');
use LinHUniX\Mcp\Model\mcpRemoteApiModelClass;
use LinHUniX\Mcp\masterControlProgram;
class queryWebCheckController  extends mcpBaseModelClass
{
    /**
     *  function moduleInit() 
     */
    protected function moduleInit(){
        $this->spacename=__NAMESPACE__;
        $this->classname=__CLASS__;
    }
    /**
     *  Ideally this method shuld be used to insert the model code and the other are to be used only as normal.
     */
    protected function moduleCore()
    {

    }
}