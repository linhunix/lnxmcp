<?php
/// MICRO CONTROLLER
$dblist=lnxmcp()->getCommon("PDO_DATABASES");
$dbstatus=array();
foreach ($dblist as $pdodrv=>$drvtype) {
    $pdosts="DEAD";
    $drv=lnxmcp()->getCfg($pdodrv);
    if ($drv!=null){
        $pdosts="LOADED";
        if ($drv instanceof LinHUniX\Mcp\Model\mcpBaseModelClass ){
            $pdosts="READY";
            if ($drv->isLive()==true){
                $pdosts="LIVE";
            }
        }
    }
    $dbstatus[$pdodrv]=array();
    $dbstatus[$pdodrv]['T']=$drvtype;
    $dbstatus[$pdodrv]['D']=$drv;
    $dbstatus[$pdodrv]['S']=$pdosts;
}
///// RENDER 
?>
<div class='hero-unit'>
    <h1>No Sql (Nsql)</h1>
    <table class='table table-bordered'>
    <tr>
        <th width='40%'>
        Database Label
        </th>
        <th width='30%'>
        Database Type
        </th>
        <th width='30%'>
        Status
        </th>
    </tr>
    <?php foreach ($dbstatus as $dname=>$dval): ?>
    <tr>
        <td>
        <?=$dname;?>
        </td>
        <td>
        <?=$dval["T"];?>
        </td>
        <td>
        <?=$dval['S'];?>
        </td>
    </tr>
    <?php endforeach; ?>
    </table>
</div>
