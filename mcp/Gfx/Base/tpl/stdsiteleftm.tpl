<!-- start site left menu !-->
<ul>
<?php 
$menu=lnxmcp()->getCommon('site.menu');
if (!is_array($menu)){
$menu=lnxmcp()->getCfg('app.site.menu');
}
if (!is_array($menu)){
    $menu=array(
        "Home"=>"/",
        "Staff Access"=>"/lnxmcpusr/login",
        "Adm Access"=>"/lnxmcpadm/login",
    );
}?>
<?php foreach($menu as $label=>$link):?>
<li>
<a href="<?=$link;?>" >
<?=$label;?>
</a>
</li>
<?php endforeach;?>
</ul>
<!-- stop site left menu !-->
