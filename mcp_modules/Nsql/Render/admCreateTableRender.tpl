<?php
 $table=null;
 if (isset($scopeIn["table"])){
     $table=$scopeIn["table"];
 }
 lnxmcpNsql("tableInit",array(),$table);
 echo "DONE";

?>
<hr>
<pre>