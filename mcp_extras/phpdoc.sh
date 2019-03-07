#!/bin/bash
set -x;
export mcp_path="$(dirname $0)/../";
export app_path="$(realpath $mcp_path )";
if [ "$app_path" == "" ]; then
    export app_path=$mcp_path ;
fi;
php $app_path/mcp_extras/phpdoc/phpDocumentor.phar -d $app_path/mcp/ -t $app_path/docs/api/
php $app_path/mcp_extras/phpdoc/phpDocumentor.phar -d $app_path/mcp_modules -t $app_path/docs/api/modules
