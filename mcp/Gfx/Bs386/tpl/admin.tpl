<?php lnxMcpTag("lnxmcp-std-head");?>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?= lnxMcpTag("lnxmcp-topmenu"); ?>
        </div>
    </div>
</div>
<br>
<br>
<div class="row">
    <div class="span4 bs-docs-sidebar">
        <div class="span3 nav nav-list" style="padding-left:10px;">
            <div class="text-center" >
                <?= lnxMcpTag("lnxmcp-logo");?>
            </div>
            <?= lnxMcpTag("lnxmcp-leftmenu");?>
        </div>
    </div>
    <div class="span10 lead">
        <?= lnxMcpTag(lnxmcp()->getCommon("page.tag")); ?>
    </div>
</div>
<?php lnxMcpTag("lnxmcp-std-foot");?>
