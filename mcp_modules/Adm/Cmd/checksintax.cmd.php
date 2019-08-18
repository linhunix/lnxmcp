<?php 

Class lnxAdmChkSintax {
    public static  $OK=0;
    public static  $KO=0;
    public static  $bincmd;
    /**
     * chkSintax
     */
    public static  function chkSintax($Pathphp) {
        try {
            $objects = scandir($Pathphp); 
            foreach ($objects as $object) { 
                if ($object != "." && $object != "..") { 
                    if ( is_dir($Pathphp."/".$object)) {
                        self::chkSintax($Pathphp."/".$object);
                    } elseif (strstr($Pathphp."/".$object,'.php')) {
                        $cmd=self::$bincmd.' -l '.$Pathphp."/".$object;
                        $res=shell_exec($cmd);
                        if (strstr($res,'No syntax errors detected')){
                            echo "OK for ".$Pathphp."/".$object.PHP_EOL;
                            self::$OK++;
                        }else{
                            self::$KO++;
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
    }
    /**
     * run
     */
    public static function run($pathapp,$phpcmd){
        echo "Run Check Syntax on ${pathapp}".PHP_EOL;
        self::$bincmd=$phpcmd;
        self::chkSintax($pathapp);
        echo "Total OK (".self::$OK.")".PHP_EOL;
        echo "Total KO (".self::$KO.")".PHP_EOL;
        if (self::$KO == 0) {
            echo "CHECK IS GOOD !!!".PHP_EOL;
            return true;
        } else {
            echo  "CHECK IS BAD !!!".PHP_EOL;
            return false;           
        }
    }    
}
lnxAdmChkSintax::run($apppath,$bincmd);
