<?php
/**
 * LinHUniX Web Application Framework
 *
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version GIT:2018-v2
 */

namespace LinHUniX\Mcp\Component;

use LinHUniX\Mcp\masterControlProgram;

/**
 * Description of mcpDebugClass
 *
 * @author andrea
 */
class mcpDebugClass {
    private $mcp;
    public function __construct(masterControlProgram $mcp) {
        $this->mcp=&$mcp;
    }
    public function debug($message) {
        if ($this->getLogger()!=null) {
            $this->getCfg ("Logger")->debug ($message);
        }
    }
    public function info($message) {
        $this->getCfg ("Logger")->info($message);
    }
    public function warning($message) {
        $this->getCfg ("Logger")->warning($message);
    }
    public function error($message) {
        $this->getCfg ("Logger")->error($message);
    }
    public function critical($message) {
        $this->getCfg ("Logger")->error($message);
        $this->header('Location: /500', true, true, 500);
    }
    public function imhere() {
        if (function_exists("debug_backtrace")) {
            $arr = debug_backtrace();
        } else {
            array(1 => array("file" => "none", "line" => 0));
        }
        if ($this->getCfg ("app.debug") == true) {
            $this->debug("[I am here]:" . $arr[1]['file'] . ":" . $arr[1]['line']);
            $imhere = "/tmp/" .$this->getCfg ("app.def") . "imhere";
            if (!isset($GLOBALS["imhere"])) {
                $GLOBALS["imhere"] = array();
                if (file_exists($imhere)) {
                    eval(file_get_contents($imhere));
                }
            }
            $GLOBALS["imhere"][$arr[1]['file']] ++;
            file_put_contents($imhere, "\$GLOBALS['imhere']=" . var_export($GLOBALS["imhere"], 1).";");
        }
    }
    public function webRem($message) {
        echo "\n<!-- ===========================================================\n";
        echo "====  LinHUniX :" . $message;
        echo "\n<=========================================================== !-->\n";
    }
    public function webDump($message, $var = null) {
        echo "\n<hr>\n";
        echo "<h2> LinHUniX :" . $message . "</h2>\n";
        echo "\n<hr>\n";
        if (!empty($var)) {
            echo "<pre>" . print_r($var, 1) . "</pre>\n";
            echo "\n<hr>\n";
        }
    }
    public function notFound($message) {
        $this->error($message);
        $this->header("HTTP/1.1 301 Moved Permanently");
        $this->header('Location:/404', true); //, true, 404);
    }
    public function move($string) {
        if (empty($string)) {
            $this->critical("Moving to Null Error");
        }
        $this->info("moving to " . $string);
        if (file_exists($GLOBALS["cfg"]["app.path"] . $string)) {
            include $GLOBALS["cfg"]["app.path"] . $string;
        } else {
            $this->critical("Moving to " . $GLOBALS["cfg"]["app.path"] . $string . " Error file not found");
        }
        exit(0);
    }
    public function header($string, $end = false, $replace = true, $retcode = null) {
        $msg = " Not End";
        if ($end) {
            $msg = " With End";
        }
        $this->error("Header [" . $retcode . "]:" . $string . $msg);
        \header($string, $replace, $retcode);
        debug_print_backtrace();
        if ($end) {
            exit(0);
        }
    }
    private function getMCP(){
        return $this->mcp;
    }
    private function getCfg($string){
        return $this->getMCP()->getCfg($string);
    }
    private function getLogger(){
        return $this->getCfg ("Logger");
    }
}
