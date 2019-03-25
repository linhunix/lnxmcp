<?php lnxMcpTag("lnxmcp-std-head");?>
<div class="off-canvas-wrapper">
    <div class="off-canvas-wrapper-inner" data-off-canvas-wrapper>
        <div class="off-canvas position-left reveal-for-large" id="my-info" data-off-canvas data-position="left">
            <div class="row column">
                <br>
                <?php lnxMcpTag("lnxmcp-logo");?>
                <?php lnxMcpTag("leftbar");?>
            </div>
        </div>
        <div class="off-canvas-content" data-off-canvas-content>
            <div class="title-bar hide-for-large">
                <div class="title-bar-left">
                    <button class="menu-icon" type="button" data-open="my-info"></button>
                    <span class="title-bar-title"><?= lnxmcp()->getCommon("page.title");?></span>
                </div>
            </div>
            <div class="callout primary">
                <div class="row column">
                <h1><?= lnxmcp()->getCommon("page.title");?></h1>
                <p class="lead"><?= lnxmcp()->getCommon("page.short.desc");?></p>
                </div>
            </div>
            <div class="row">
                <?= lnxMcpTag(lnxmcp()->getCommon("page.tag")); ?>
            </div>
        </div>
    </div>
</div>
<?php lnxMcpTag("lnxmcp-std-foot");?>