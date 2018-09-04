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

    public function __construct ($dir="")
    {
        if ($dir==""){
            $dir=__DIR__;
        }
        $this->getDirContents ($dir);
        $this->load ();
    }
    private function getDirContents ($dir)
    {
        $files = scandir ($dir);
        foreach ($files as $key => $value) {
            $path = realpath ($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir ($path)) {
                $this->results[] = $path;
            } else if ($value != "." && $value != "..") {
                $this->getDirContents ($path);
                $this->results[] = $path;
            }
        }
    }

    private function load ()
    {
        foreach ($this->results as $file) {
            $file_parts = pathinfo ($file);
            if ($file_parts['extension'] = "php") {
                include $file;
            }
        }
    }
}

/// AUTO RUN
if (!isset($mcp_path)){
    $mcp_path=__DIR__."/";
}
$mcp_autoload=new mcpAutoload($mcp_path);