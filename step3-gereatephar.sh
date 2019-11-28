#!/bin/bash
clear;
echo "LNX STEP 3 - GENERATE PHAR FILE "
export MCP_HOME="$(dirname $0)";
export MCP_VERSION="$(grep 'Actual Version' $MCP_HOME/releases.txt| cut -f 2 -d ':' )";
export MCP_TOPIC="$(grep 'Actual Topic' $MCP_HOME/releases.txt| cut -f 2 -d ':' )";
export MCP_SERVER="$(hostname)";
export MCP_USER="$USERNAME";
export MCP_RELEASE="$MCP_VERSION.$(date +%Y%m%d)";
echo "$MCP_RELEASE - $MCP_TOPIC - $MCP_USER@$MCP_SERVER - $(date) ">>$MCP_HOME/releases.txt;
echo "$MCP_RELEASE" >$MCP_HOME/mcp/mcp_version ;
$MCP_HOME/mcp_extras/pharize.sh ;
if [ -d $MCP_HOME/../lnxmcp-docs/ ]; then
    cp $MCP_HOME/dist/lnxmcp.phar $MCP_HOME/../lnxmcp-docs/mcp/
fi;