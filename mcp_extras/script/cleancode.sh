#!/bin/bash
find ./  -type f -name '*.php' | while read rr; do  {
	dos2unix "$rr";
	sed -e 's/type="radio"value=/type="radio" value=/g' -i "$rr" ;
	sed -e 's/type="radio"id=/type="radio" id=/g' -i "$rr" ;
}; done 
