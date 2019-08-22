<?php
$cmd=$bincmd. ' -S 127.0.0.1:9090 -t'.$apppath;
echo "Run Httpd server [".$cmd."]".PHP_EOL;
shell_exec($cmd);