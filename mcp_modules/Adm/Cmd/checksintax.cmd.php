<?php 
/**
 * Check PAth
 */
function ChkSintax($Pathphp,$bincmd) {
    $cnt=array(
        'OK'=>0,
        'KO'=>0
    );
    try {
        $objects = scandir($Pathphp); 
        foreach ($objects as $object) { 
            if ($object != "." && $object != "..") { 
                if ( is_dir($Pathphp."/".$object)) {
                    $ncnt=ChkSintax($Pathphp."/".$object,$bincmd);
                    $cnt['OK']+=$ncnt['OK'];
                    $cnt['KO']+=$ncnt['KO'];
                } elseif (strstr($Pathphp."/".$object,'.php')) {
                    $cmd=$bincmd.' -l '.$Pathphp."/".$object;
                    $res=shell_exec($cmd);
                    if (strstr($res,'No syntax errors detected')){
                        echo "OK for ".$Pathphp."/".$object.PHP_EOL;
                        $cnt['OK']++;
                    }else{
                        $cnt['KO']++;
                        echo "KO for ".$Pathphp."/".$object.PHP_EOL;
                        print_r($res);
                        echo PHP_EOL;
                    }
                }
            }
        } 
    } catch (Exception $e)
    {
        $this->getMcp()->warning("Can execute check ".$e->getMessage());
    }
    return $cnt;
}
echo "Run Check Syntax on ${apppath}".PHP_EOL;
$tot=ChkSintax($apppath,$bincmd);
echo "Total OK (".$tot['OK'].")".PHP_EOL;
echo "Total KO (".$tot['KO'].")".PHP_EOL;
