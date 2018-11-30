#!/bin/bash
if [ "$1" == "" ] ; then echo "need a path "; exit 1 ; fi;
if [ ! -d "$1" ] ; then echo "path is not a directory "; exit 2; fi;

path="$1";
find $path -type f -name '*.php' | while read rr ; do
 if [ "$( grep '#LNXMCP#' "$rr" )" == "" ];then {
 sed -i '1 i\<?php /* #LNXMCP#START#LNXMCP# */ if(!isset($GLOBALS["cfg"])){require_once($_SERVER['DOCUMENT_ROOT']."/Head.php");};$GLOBALS["mcp"]->imhere();/* #LNXMCP#STOP#LNXMCP# */ ?>' "$rr" ;
 }
done

