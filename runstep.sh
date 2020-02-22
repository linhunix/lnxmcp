#!/bin/sh
./step1-chkcode.sh
read 
./step2-chk.sh
read 
./step2-chk.sh mcp/test_common
read 
./step2-chk.sh mcp/chkController
read 
./step3-gereatephar.sh
read 
./step4-generatedoc.sh

