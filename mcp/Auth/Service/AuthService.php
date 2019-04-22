<?php

/**
 * LinHUniX Web Application Framework
 *
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 */

namespace LinHUniX\Auth\Service;

use LinHUniX\Mcp\Model\mcpServiceModelClass;

class AuthService extends mcpServiceModelClass {
    protected $session_id;
    protected $session_user;
    protected $session_group;
    protected $session_data;
    protected function moduleSingleTon() {
        $this->session_id=date("U");
        $this->session_user="guest";
        $this->session_group="guest";
        if (isset($_COOKIE["lnxmcp"])) {
            $this->$session_id=$_COOKIE["lnxmcp"];
        }
        $this->getMcp()->setCommon("session.id",$session_id);
        $this->session_data=$this->callCmd($this->getSvcCfg("Session.command"),$this->getSvcCfg());
        if (isset($this->session_data["user"])){
            $this->session_user=$this->session_data["user"];
        }
        if (isset($this->session_data["group"])){
            $this->session_user=$this->session_data["group"];
        }
        $this->getMcp()->setCommon("session.user",$this->session_user);
        $this->getMcp()->setCommon("session.group",$this->session_group);
        $this->getMcp()->setCommon("session.data",$this->session_data);
    }
}