#!/bin/bash
clear;
echo "LNX STEP 4 - GENERATE DOCS API FILE "
export MCP_HOME="$(dirname $0)";
if [ -d $MCP_HOME/../lnxmcp-docs/ ]; then
    $MCP_HOME/mcp_extras/phpdoc.sh ;
fi;