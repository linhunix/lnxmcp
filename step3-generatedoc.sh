#!/bin/bash
clear;
echo "LNX STEP 3 - GENERATE PHAR FILE "
export MCP_HOME="$(dirname $0)";
$MCP_HOME/mcp_extras/phpdoc.sh ;