#!/bin/bash
clear;
echo "LNX STEP 1 - CHECK "
export MCP_HOME="$(dirname $0)";
$MCP_HOME/mcp_example/webcheck/chk.sh $@ ;
