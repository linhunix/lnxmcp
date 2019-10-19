    <div class="hero-unit">
      <h1>No Sql (Nsql)</h1>
     <table class="table table-bordered">
        <tr>
            <th width='20%'>
            Label
            </th>
            <th width='40%'>
            Database Type
            </th>
            <th width='40%'>
            Table
            </th>
        </tr>
        <tr>
            <td>
            Config
            </td>
            <td>
            <lnxmcp block-type='config' >app.nsql.dbtype</lnxmcp>
            </td>
            <td>
            <lnxmcp block-type='config' >app.nsql.dbtable</lnxmcp>
            </td>
        </tr>
        <tr>
            <lnxmcp type='Render' name='info' module='Nsql' vendor='LinHUniX' >
            <td>
            Service
            </td>
            <td>
            {{dbtype}}
            </td>
            <td>
            {{dbtable}}
            </td>
            </lnxmcp>
        </tr>
      </table>
    </div>
    <div class="tabbable"> 
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab0" data-toggle="tab">Select-></a></li>
        <li><a href="#tabL" data-toggle="tab">List</a></li>
        <li><a href="#tabF" data-toggle="tab">Find</a></li>
        <li><a href="#tabS" data-toggle="tab">Show</a></li>
        <li><a href="#tabC" data-toggle="tab">Create</a></li>
        <li><a href="#tabRV" data-toggle="tab">Read value</a></li>
        <li><a href="#tabEV" data-toggle="tab">Edit value</a></li>
        <li><a href="#tabD" data-toggle="tab">Delete</a></li>
        <li><a href="#tabCT" data-toggle="tab">Create Table</a></li>
      </ul>
    <div class="hero-unit">
      <div class="tab-content">
        <div class="tab-pane active" id="tab0">
            Select Your Request
        </div>
        <div class="tab-pane" id="tabL">
          <h1>List Document</h1>
              <form id='listdoc' action='/lnxmcpapi' method='post' target='result' >
                <input name='type' type="hidden" value="Render" >
                <input name='ispreload' type="hidden" value="false" >
                <input name='name' type="hidden" value="admList" >
                <input name='module' type="hidden" value="Nsql" >
                <input name='vendor' type="hidden" value="LinHUniX" >
                <fieldset>
                    <legend>Request</legend>
                    <hr>
                    <label>Table</label>
                    <input name='table' type="text" placeholder="name of the table or null">
                    <hr>
                    <button type="submit" class="btn">Submit</button>
                </fieldset>
            </form>
        </div>
        <div class="tab-pane" id="tabF">
          <h1>Find Documents</h1>
        </div>
        <div class="tab-pane" id="tabS">
          <h1>Show Document</h1>
        </div>
        <div class="tab-pane" id="tabC">
          <h1>Create Document</h1>
        </div>
        <div class="tab-pane" id="tabRV">
          <h1>Read Value</h1>
        </div>
        <div class="tab-pane" id="tabEV">
          <h1>Edit Value</h1>
        </div>
        <div class="tab-pane" id="tabD">
          <h1>Delte Documnent</h1>
        </div>
        <div class="tab-pane" id="tabCT">
          <h1>Create Table</h1>
        </div>
      </div>
      </div>
    </div>
    <iframe width='100%' name='result' style='background-color:white;width:100%;height:400px;'></iframe>