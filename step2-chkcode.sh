#!/bin/bash
clear;
echo "LNX STEP 2 - CHECK CODE"
export MCP_HOME="$(dirname $0)";
find $MCP_HOME -type f  -name '*.php'| while read rr ; do
    echo "- Check $rr.....";
    php -l "$rr" 2>&1;
done;