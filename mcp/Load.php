<?php

/**
 * Created by PhpStorm.
 * User: LinHUniX Andrea Morello
 * Date: 9/4/2018
 * Time: 9:22 PM
 */
class mcpAutoload
{
    public $results;
    private $count = 0;

    public function __construct($dir = "")
    {
        if ($dir == "") {
            $dir = __DIR__;
        }
        $this->results = array();
        $this->getDirContents($dir);
        $this->load();
    }

    function setres($resval)
    {
        $this->results[$this->count] = $resval;
        $this->count++;
    }
    
    private function getDirContents($dir)
    {
        $files = scandir($dir);
        foreach ($files as $key => $value) {
            $path = $dir . DIRECTORY_SEPARATOR . $value;
            if (is_dir($path) == false) {
                $this->setres($path);
            } else if ($value != "." && $value != "..") {
                if (file_exists($path . DIRECTORY_SEPARATOR . "mcp.autoload.php")) {
                    $this->setres($path . DIRECTORY_SEPARATOR . "mcp.autoload.php");
                } else {
                    $this->getDirContents($path);
                }
            }
        }
    }

    private function load()
    {
        foreach ($this->results as $file) {
            $file_parts = pathinfo($file);
            if (isset($file_parts['extension'])) {
                if ($file_parts['extension'] == "php") {
                    include_once $file;
                }
            } else {
                if (substr($file, -3) == "php") {
                    include_once $file;
                }
            }
        }
    }
}

/// AUTO RUN
$mcp_autoload = new mcpAutoload($mcp_path . "/Mcp");
$mcp_autoload = new mcpAutoload($mcp_path . "/Pdo");
$mcp_autoload = new mcpAutoload($mcp_path . "/Mail");
$mcp_autoload = new mcpAutoload($mcp_path . "/Gfx");
$mcp_autoload = new mcpAutoload($mcp_path . "/ln4");
