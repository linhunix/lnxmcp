<?php lnxMcpTag("lnxmcp-std-head");?>
<br>
<div class="row">
    <div class="span4 bs-docs-sidebar">
        <div class="span3 nav nav-list" style="padding-left:10px;">
            <div class="text-center" >
                <?= lnxMcpTag("lnxmcp-logo");?>
                <h2><?= lnxmcp()->getCfg("app.def");?></h2>
                <hr>
            </div>
        </div>
    </div>
    <div class="span10 lead hero-unit">
    <form action='/lnxmcpadm' method='post' target='result' >
      <fieldset>
        <legend>Login</legend>
        <label>User</label>
        <input name='user' type="text" placeholder="">
        <label>Pass</label>
        <input name='pass' type="Password" placeholder="">
        <button type="submit" class="btn">Submit</button>
      </fieldset>
    </form>
    </div>
</div>
<?php lnxMcpTag("lnxmcp-std-foot");?>
