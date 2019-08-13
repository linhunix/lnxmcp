<?php

echo '//////////////////////////////////////////////////////////'.PHP_EOL;
echo '|-> PATH APP:'.$apppath.'('.$appok.')'.PHP_EOL;
echo '|-> PATH IDX:'.$idxpath.'('.$idxok.')'.PHP_EOL;
echo '|-> PATH CFG:'.$cfgpath.'('.$cfgok.')'.PHP_EOL;
echo '|-> PATH STS:'.$setpath.'('.$setok.')'.PHP_EOL;
echo '|-> PATH MCP:'.$mcpath.PHP_EOL;
echo '|-> PATH ADM:'.$admpath.PHP_EOL;
echo '|-> PATH PHP:'.$bincmd.PHP_EOL;
echo '|-> VERS PHP:'.PHP_VERSION.PHP_EOL;
echo '|-> VERS SYS:'.PHP_OS.PHP_EOL;
echo '|-> NAME SYS:'.$_SERVER['HOSTNAME'].PHP_EOL;
echo '//////////////////////////////////////////////////////////'.PHP_EOL;
echo '| Command List'.PHP_EOL;
echo '//////////////////////////////////////////////////////////'.PHP_EOL;
foreach (scandir(__DIR__) as $cfile) {
    if (strstr($cfile, 'cmd.php') != false) {
        $ctag = explode('.', $cfile);
        echo '|-> '.$ctag[0];
        $cdsc = __DIR__.'/'.$ctag[0].'.cmd.txt';
        if (file_exists($cdsc)) {
            echo ' : '.file_get_contents($cdsc);
        }
        echo PHP_EOL;
    }
}
echo '//////////////////////////////////////////////////////////'.PHP_EOL;
