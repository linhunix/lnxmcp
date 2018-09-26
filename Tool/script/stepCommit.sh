#!/bin/bash
let a=1; 
git status | tr -s ' ' ' ' | grep modified | cut -f 2 -d ":" | while read rr; 
do 
    let a++ ; 
    if [ "$a" == "100" ]; then {  
	exit 1 ; 
    }; fi ; 
    if [ -f "$rr" ] ; then {
	echo "add n $a : $rr ";
        git add "$rr" ; 
    }; fi;
done

