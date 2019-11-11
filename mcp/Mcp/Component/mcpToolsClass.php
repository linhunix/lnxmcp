<?php

/**
 * LinHUniX Web Application Framework.
 *
 * @author Andrea Morello <andrea.morello@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 *
 * @version GIT:2018-v2
 */

namespace LinHUniX\Mcp\Component;

/**
 * Description of mcpToolsClass.
 *
 * @author andrea
 */
class mcpToolsClass
{
    /**
     * Clear Escape char.
     *
     * @param string $string
     *
     * @return string
     */
    public function escapeClear($string)
    {
        return preg_replace('~[\x00\x0A\x0D\x1A\x22\x27\x5C]~u', '\\\$0', $string);
    }

    /**
     * clear string from strange chars.
     *
     * @param string $str
     *
     * @return string
     */
    public function toAscii($str)
    {
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", '-', $clean);

        return $clean;
    }

    /**
     * Request save to session.
     *
     * @param type $arguments name of the request
     * @param type $onlyPost  if true don-t read get
     */
    public function Req2Session($arguments, $onlyPost = false)
    {
        if (!$onlyPost) {
            if (isset($_GET[$arguments])) {
                $_SESSION[$arguments] = $_GET[$arguments];
            }
        }
        if (isset($_POST[$arguments])) {
            $_SESSION[$arguments] = $_POST[$arguments];
        }
        if (!isset($_SESSION[$arguments])) {
            $_SESSION[$arguments] = '';
        }
        if ($_SESSION[$arguments] == 'Reset') {
            $_SESSION[$arguments] = '';
            $_REQUEST[$arguments] = '';
            $_GET[$arguments] = '';
            $_POST[$arguments] = '';
        }
        if ($_SESSION[$arguments] == '') {
            $_SESSION[$arguments] = '';
            $_GET[$arguments] = '';
            $_POST[$arguments] = '';
        }
    }

    /**
     * Object2Array function.
     *
     * @param object $data
     *
     * @return array
     */
    public function Object2Array($data)
    {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key] = $this->Object2Array($value);
            }

            return $result;
        }

        return $data;
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
        } elseif (file_exists($path.DIRECTORY_SEPARATOR.$string.'.'.$ext)) {
            include $path.DIRECTORY_SEPARATOR.$string.'.'.$ext;   
        } elseif (file_exists($path.$default.$ext)) {
            include $path.$default.$ext;
        } elseif (file_exists($path.DIRECTORY_SEPARATOR.$default.'.'.$ext)) {
            include $path.DIRECTORY_SEPARATOR.$default.'.'.$ext;
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
            flush();
            exit(0);
        }
    }
}
