<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?= lnxmcp()->getCommon("title");?></title>
        <?php lnxMcpTag("common.meta");?>
        <?php lnxMcpTag("lnxmcp-themecss");?>
        <?php lnxMcpTag("common.css");?>
    </head>
    <body>
        <?php lnxMcpTag("common.head");?>
        <div class="off-canvas-wrapper">
            <div class="off-canvas-wrapper-inner" data-off-canvas-wrapper>
                <div class="off-canvas position-left reveal-for-large" id="my-info" data-off-canvas data-position="left">
                    <div class="row column">
                        <br>
                        <?php lnxMcpTag("logo");?>
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
        <?php lnxMcpTag("common.foot");?>
        <?php lnxMcpTag("lnxmcp-themejs");?>
        <?php lnxMcpTag("common.js");?>
    </body>
</html>