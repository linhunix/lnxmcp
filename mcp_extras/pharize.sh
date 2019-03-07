#!/bin/bash
set -x;
export mcp_path="$(dirname $0)/../";
export app_path="$(realpath $mcp_path )";
if [ "$app_path" == "" ]; then
    export app_path=$mcp_path ;
fi;

cd $app_path;
php -d phar.readonly=0 $app_path/mcp_extras/pharize.php 2>&1 ;