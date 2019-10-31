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
        <li><a href="#tabFO" data-toggle="tab">Find Oper.</a></li>
        <li><a href="#tabS" data-toggle="tab">Show</a></li>
        <li><a href="#tabC" data-toggle="tab">Create</a></li>
        <li><a href="#tabRV" data-toggle="tab">Read value</a></li>
        <li><a href="#tabEV" data-toggle="tab">Make value</a></li>
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
            <form id='finddoc' action='/lnxmcpapi' method='post' target='result' >
                <input name='type' type="hidden" value="Render" >
                <input name='ispreload' type="hidden" value="false" >
                <input name='name' type="hidden" value="admFind" >
                <input name='module' type="hidden" value="Nsql" >
                <input name='vendor' type="hidden" value="LinHUniX" >
                <fieldset>
                    <legend>Request</legend>
                    <hr>
                    <label>Table</label>
                    <input name='table' type="text" placeholder="name of the table or null">
                    <hr>
                    <label>Name</label>
                    <input type='text' name='doc_name' >
                    <label>Find</label>
                    <input type='text' name='doc_find' >
                    <label>docidlist</label>
                    <input type='text' name='doc_idx' >
                    <hr>
                    <button type="submit" class="btn">Submit</button>
                </fieldset>
            </form>
        </div>
        <div class="tab-pane" id="tabFO">
          <h1>Find Documents</h1>
            <form id='finddoc' action='/lnxmcpapi' method='post' target='result' >
                <input name='type' type="hidden" value="Render" >
                <input name='ispreload' type="hidden" value="false" >
                <input name='name' type="hidden" value="admFind" >
                <input name='module' type="hidden" value="Nsql" >
                <input name='vendor' type="hidden" value="LinHUniX" >
                <fieldset>
                    <legend>Request</legend>
                    <hr>
                    <label>Table</label>
                    <input name='table' type="text" placeholder="name of the table or null">
                    <hr>
                    <label>Operator</label>
                    <select name='doc_srcopt'>
                    <option value='='> Equal </option>
                    <option value='<>'> Not Equal </option>
                    <option value='>'> Upper </option>
                    <option value='<'> Lower </option>
                    </select>
                    <label>Name</label>
                    <input type='text' name='doc_name' >
                    <label>Find</label>
                    <input type='text' name='doc_find' >
                    <label>docidlist</label>
                    <input type='text' name='doc_idx' >
                    <hr>
                    <button type="submit" class="btn">Submit</button>
                </fieldset>
            </form>
        </div>
        <div class="tab-pane" id="tabS">
          <h1>Show Document</h1>
            <form id='showdoc' action='/lnxmcpapi' method='post' target='result' >
                <input name='type' type="hidden" value="Render" >
                <input name='ispreload' type="hidden" value="false" >
                <input name='name' type="hidden" value="admShow" >
                <input name='module' type="hidden" value="Nsql" >
                <input name='vendor' type="hidden" value="LinHUniX" >
                <fieldset>
                    <legend>Request</legend>
                    <hr>
                    <label>Table</label>
                    <input name='table' type="text" placeholder="name of the table or null">
                    <hr>
                    <label>Doc Id</label>
                    <input type='text' name='doc_id' >
                    <hr>
                    <button type="submit" class="btn">Submit</button>
                </fieldset>
            </form>
        </div>
        <div class="tab-pane" id="tabC">
          <h1>Create Document</h1>
            <form id='makedoc' action='/lnxmcpapi' method='post' target='result' >
                <input name='type' type="hidden" value="Render" >
                <input name='ispreload' type="hidden" value="false" >
                <input name='name' type="hidden" value="admCreate" >
                <input name='module' type="hidden" value="Nsql" >
                <input name='vendor' type="hidden" value="LinHUniX" >
                <fieldset>
                    <legend>Request</legend>
                    <hr>
                    <label>Table</label>
                    <input name='table' type="text" placeholder="name of the table or null">
                    <hr>
                    <label>Doc Id</label>
                    <input type='text' name='doc_id' >
                    <label>Doc Name</label>
                    <input type='text' name='doc_name' >
                    <hr>
                    <button type="submit" class="btn">Submit</button>
                </fieldset>
            </form>
        </div>
        <div class="tab-pane" id="tabRV">
          <h1>Read Value</h1>
            <form id='readval' action='/lnxmcpapi' method='post' target='result' >
                <input name='type' type="hidden" value="Render" >
                <input name='ispreload' type="hidden" value="false" >
                <input name='name' type="hidden" value="admGetVal" >
                <input name='module' type="hidden" value="Nsql" >
                <input name='vendor' type="hidden" value="LinHUniX" >
                <fieldset>
                    <legend>Request</legend>
                    <hr>
                    <label>Table</label>
                    <input name='table' type="text" placeholder="name of the table or null">
                    <hr>
                    <label>Doc Id</label>
                    <input type='text' name='doc_id' >
                    <label>Doc Name</label>
                    <input type='text' name='doc_name' >
                    <hr>
                    <button type="submit" class="btn">Submit</button>
                </fieldset>
            </form>
        </div>
        <div class="tab-pane" id="tabEV">
          <h1>Make Value</h1>
                <form id='makedoc' action='/lnxmcpapi' method='post' target='result' >
                <input name='type' type="hidden" value="Render" >
                <input name='ispreload' type="hidden" value="false" >
                <input name='name' type="hidden" value="admSetVal" >
                <input name='module' type="hidden" value="Nsql" >
                <input name='vendor' type="hidden" value="LinHUniX" >
                <fieldset>
                    <legend>Request</legend>
                    <hr>
                    <label>Table</label>
                    <input name='table' type="text" placeholder="name of the table or null">
                    <hr>
                    <label>Doc Id</label>
                    <input type='text' name='doc_id' >
                    <label>Doc Name</label>
                    <input type='text' name='doc_name' >
                    <label>Doc Value</label>
                    <input type='text' name='doc_value' >
                    <hr>
                    <button type="submit" class="btn">Submit</button>
                </fieldset>
            </form>
        </div>
        <div class="tab-pane" id="tabD">
          <h1>Delete Documnent</h1>
            <form id='deldoc' action='/lnxmcpapi' method='post' target='result' >
                <input name='type' type="hidden" value="Render" >
                <input name='ispreload' type="hidden" value="false" >
                <input name='name' type="hidden" value="admDelete" >
                <input name='module' type="hidden" value="Nsql" >
                <input name='vendor' type="hidden" value="LinHUniX" >
                <fieldset>
                    <legend>Request</legend>
                    <hr>
                    <label>Table</label>
                    <input name='table' type="text" placeholder="name of the table or null">
                    <hr>
                    <label>Doc Id</label>
                    <input type='text' name='doc_id' >
                    <hr>
                    <button type="submit" class="btn">Submit</button>
                </fieldset>
            </form>
        </div>
        <div class="tab-pane" id="tabCT">
          <h1>Create Table</h1>
          <form id='newtabledoc' action='/lnxmcpapi' method='post' target='result' >
                <input name='type' type="hidden" value="Render" >
                <input name='ispreload' type="hidden" value="false" >
                <input name='name' type="hidden" value="admCreateTable" >
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
      </div>
      </div>
    </div>
    <iframe width='100%' name='result' style='background-color:white;width:100%;height:400px;'></iframe>