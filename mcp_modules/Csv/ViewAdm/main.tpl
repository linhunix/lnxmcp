    <?php lnxmcpUpload(array("category"=>"csv","allowlist"=>array("csv"))); ?>
    <form id='csvupload' action='/lnxmcpadm/Csv' method='post' target='result'   enctype="multipart/form-data" >
      <fieldset>
        <legend>Test Csv</legend>
        <label>Table</label>
        <input name='csv' type="file" placeholder="name of the table or null">
        <br>
        <hr>
        <input type="submit" value="Upload">
        <HR>
        <pre>
        <?php print_r(lnxmcpFileList(array("category"=>"csv","allowlist"=>array("csv")))); ?>
        </pre>
        <HR>
      </fieldset>
    </form>
    <iframe width='100%' name='result' style='background-color:white;width:100%;height:400px;'></iframe>