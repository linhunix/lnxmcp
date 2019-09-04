<?php
/**
 * LinHUniX Web Application Framework.
 *
 * @author    Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 *
 * @version   GIT:2018-v2
 */

namespace LinHUniX\Mcp\Component;

use LinHUniX\Mcp\masterControlProgram;
use LinHUniX\Mcp\Provider\loggerProviderModel;

/**
 * Description of mcpDebugClass.
 *
 * @author andrea
 */
class mcpDebugClass
{
    private $mcp;

    /**
     * __construct.
     *
     * @param mixed $mcp
     */
    public function __construct(masterControlProgram &$mcp)
    {
        $this->mcp = &$mcp;
        if ($this->getLogger() == null) {
            if ($mcp->getResource(masterControlProgram::CLASS_LOGGER) != null) {
                $mcp->register(new ${$mcp->getResource(masterControlProgram::CLASS_LOGGER)}());
            } else {
                $mcp->register(new loggerProviderModel());
            }
        }
    }

    /**
     * debug.
     *
     * @param mixed $message
     */
    public function debug($message)
    {
        $this->getLogger()->debug($message);
    }

    /**
     * info.
     *
     * @param mixed $message
     */
    public function info($message)
    {
        $this->getLogger()->info($message);
    }

    /**
     * warning.
     *
     * @param mixed $message
     */
    public function warning($message)
    {
        $this->getLogger()->warning($message);
    }

    /**
     * error.
     *
     * @param mixed $message
     */
    public function error($message)
    {
        $this->getLogger()->error($message);
    }

    /**
     * critical.
     *
     * @param mixed $message
     */
    public function critical($message)
    {
        $this->getLogger()->error($message);
        mcpMailClass::supportmail($message);
        $this->header('Location: /500', true, true, 500);
    }

    /**
     * imhere.
     */
    public function imhere()
    {
        if ($this->getRes('debug') == true) {
            if (function_exists('debug_backtrace')) {
                $arr = debug_backtrace();
            } else {
                array(1 => array('file' => 'none', 'line' => 0));
            }
            $this->debug('[I am here]:'.$arr[1]['file'].':'.$arr[1]['line']);
            $path = $this->getRes('path.pbkac');
            lnxUpdJsonFile($arr[1]['file'].':'.$arr[1]['line'], '++', $this->getRes('def'), $path, 'imhere');
        }
    }

    /**
     * webRem.
     *
     * @param mixed $message
     * @param mixed $var
     */
    public function webRem($message, $var = null)
    {
        if ($this->getRes('web.rem') == false) {
            return;
        }
        echo "\n<!-- ======================================================= ===";
        echo "\n====  ".$this->getRes('def').' :'.print_r($message, 1);
        if (!empty($var)) {
            echo "\n==== ======================================================= ===";
            echo "\n====  ".print_r($var, 1);
        }
        echo "\n==== ======================================================= !-->";
        echo "\n";
    }

    /**
     * webDebugRem show rem if are in debug enable.
     *
     * @param mixed $message
     * @param mixed $var
     */
    public function webDebugRem($message, $var = null)
    {
        if ($this->getLogger()->IsDebug()) {
            $this->webRem($message, $var);
        }
    }

    /**
     * webDump.
     *
     * @param mixed $message
     * @param mixed $var
     */
    public function webDump($message, $var = null)
    {
        if ($this->getRes('web.dump') == false) {
            return;
        }
        echo "\n<hr>\n";
        echo '<h2> '.$this->getRes('def').' :'.$message."</h2>\n";
        echo "\n<hr>\n";
        if (!empty($var)) {
            echo '<pre>'.print_r($var, 1)."</pre>\n";
            echo "\n<hr>\n";
        }
    }

    public function jsDumpScript($name, array $scopeIn)
    {
        echo "<script type='text/javascript' >\n";
        try {
            echo 'window.'.$name.'='.json_encode($scopeIn, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            echo "alert('".$e->getMessage()."');\n";
        }
        echo "</script>\n";
    }

    public function jsonDump(array $scopeIn)
    {
        try {
            echo json_encode($scopeIn, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            echo "{\n'error':'".$e->getMessage()."'}\n";
        }
    }

    /**
     * notFound.
     *
     * @param mixed $message
     */
    public function notFound($message)
    {
        $this->error($message);
        $this->header('HTTP/1.1 301 Moved Permanently');
        $this->header('Location:/404', true); //, true, 404);
    }

    /**
     * move.
     *
     * @param mixed $string
     */
    public function move($string, $default = null, $ext = '', $path = null, $andEnd = true)
    {
        if (empty($string)) {
            $this->critical('Moving to Null Error');
        }
        $this->info('moving to '.$string);
        if ($path == null) {
            $path = $this->getRes('path');
        }
        if ($default == null) {
            $default = $string;
        }
        if (file_exists($path.$string.$ext)) {
            include $path.$string.$ext;
        } elseif (file_exists($path.$default.$ext)) {
            include $path.$default.$ext;
        } else {
            $this->critical('Moving to '.$path.$string.' Error file not found');
        }
        if ($andEnd == true) {
            exit(0);
        }
    }

    /**
     * header.
     *
     * @param mixed $string
     * @param mixed $end
     * @param mixed $replace
     * @param mixed $retcode
     */
    public function header($string, $end = false, $replace = false, $retcode = null, $htmljs = false)
    {
        $msg = ' Not End';
        if ($end) {
            $msg = ' With End';
        }
        $this->warning('Header ['.$retcode.']:'.$string.$msg);
        if ($htmljs == false) {
            \header_remove();
            \header($string, $replace, $retcode);
        } else {
            $he = explode(':', $string);
            $heval = array_shift($he);
            $heres = implode(':', $he);
            if (stristr($heval, 'location') != false) {
                echo '<script type="text/javascript">';
                echo ' window.location="'.$heres.'";';
                echo '</script>';
            } else {
                echo '<meta http-equiv="'.$heval.'" content="'.$heres.'" >';
            }
        }
        if ($end) {
            exit(0);
        }
    }

    /**
     * getMCP.
     */
    private function getMCP()
    {
        return $this->mcp;
    }

    /**
     * getCfg.
     *
     * @param mixed $string
     */
    private function getRes($string)
    {
        return $this->getMCP()->getResource($string);
    }

    /**
     * getLogger.
     */
    private function getLogger()
    {
        return $this->getMCP()->getCfg('Logger');
    }
}
