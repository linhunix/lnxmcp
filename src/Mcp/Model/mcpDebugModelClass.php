<?php
/**
 * Created by PhpStorm.
 * User: freetimers
 * Date: 9/18/2018
 * Time: 4:34 PM
 */

namespace LinHUniX\Mcp\Model;

use LinHUniX\Mcp\masterControlProgram;

class mcpDebugModelClass
{
    const FATAL = 100;
    const ERROR = 75;
    const WARN = 50;
    const INFO = 25;
    const DEBUG = 0;
    private $level = 0;
    private $setting;
    private $mcp;

    public function __construct (masterControlProgram &$mcp, $level, array $setting = array ())
    {
        $this->mcp = $mcp;
        $this->level = intval ($level);
        $this->setting = $setting;
    }

    public function GetLevel ()
    {
        return $this->level;
    }

    public function IsValid ($mylevel)
    {
        try {
            $mylvl = intval ($mylevel);
            if ($this->level <= $mylvl) {
                return true;
            }
        } catch (Exception $e) {
            $this->warning ("wrong level");
        }
        return false;
    }

    public function GetSetting ($name)
    {
        if (in_array ($name, $this->setting)) {
            return $this->setting[$name];
        }
        return null;
    }

    public function Debug ($message)
    {
        if ($this->IsValid (self::DEBUG)) {
            $this->writelog (self::DEBUG, $message);
        }
    }

    public function info ($message)
    {
        if ($this->IsValid (self::INFO)) {
            $this->writelog (self::INFO, $message);
        }
    }

    public function warning ($message)
    {
        if ($this->IsValid (self::WARN)) {
            $this->writelog (self::WARN, $message);
        }
    }

    public function error ($message)
    {
        if ($this->IsValid (self::ERROR)) {
            $this->writelog (self::ERROR, $message);
        }
    }

    public function fatal ($message)
    {
        if ($this->IsValid (self::FATAL)) {
            $this->writelog (self::FATAL, $message);
        }
    }

    public function writelog ($level, $message)
    {
        $app = $this->mcp->getCfg ("app.def");
        $time = date ();
        error_log ("[" . $app . "][" . $time . "][" . $level . "]:" . $message);
    }
}