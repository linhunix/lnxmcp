<html>
<head>
    <Title>LinHUniX ltd - Master Control Program - CopyRight</Title>
</head>
<body>
    <h1>LinHUniX ltd - Master Control Program  ver <?=lnxmcp()->getCfg("mcp.ver");?></h1>
<hr>CopyRight<hr>
    <ul>
        <li>Licenze:<b> Gpl V3</b></li>
        <li>Author:<b> Andrea Morello  - andrea.morello@unix.team </b></li>
        <li>CopyRight:<b> 2003/2020 </b></li>
        <li>Version:<b> <?=lnxmcp()->getCfg("mcp.ver");?></b></li>
        <li>Url:<b> https://github.com/linhunix/lnxmcp</b></li>
        <li>Documentation:<b> https://github.com/linhunix/lnxmcp/wiki</b></li>
    </ul>
<hr>Cfg<hr>
    <ul>
    <?php foreach (lnxmcp()->getCfg() as $def=>$value){
        echo "<li>".$def.":<b>";
        if (is_array($value)){
            echo "Array";
        } else if (is_numeric($value)){
            echo "Number";
        } else if (is_string($value)){
            echo "String";
        } else if (is_bool($value)){
            echo "bool";
        } else if (is_callable($value)){
            echo "class/function";
        } else {
            echo "ready";
        }
        echo "</b></li>\n";
    } ?>
    </ul>
 <hr>Common<hr>
    <ul>
    <?php foreach (lnxmcp()->getCommon() as $def=>$value){
        echo "<li>".$def.":<b>";
        if (is_array($value)){
            echo "Array";
        } else if (is_numeric($value)){
            echo "Number=".$value;
        } else if (is_string($value)){
            echo "String=".$value;
        } else if (is_bool($value)){
            echo "bool=".var_export($value,1);
        } else if (is_callable($value)){
            echo "class/function";
        } else {
            echo "ready";
        }
        echo "</b></li>\n";
    } ?>
    </ul>

</body>
</html>