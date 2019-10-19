    <form action='/lnxmcpapi' method='post' target='result' >
      <input name='ispreload' type="hidden" value="false" >
      <fieldset>
        <legend>Test Api</legend>
        <label>Type of Command</label>
        <input name='type' type="text" placeholder="Type type api">
        <label>Name</label>
        <input name='name' type="text" placeholder="Type name api">
        <label>Module</label>
        <input name='module' type="text" placeholder="Type module api">
        <label>Vendor</label>
        <input name='vendor' type="text" placeholder="Type vendor api">
        <button type="submit" class="btn">Submit</button>
      </fieldset>
    </form>
    <iframe width='100%' name='result' style='background-color:white;width:100%;height:400px;'></iframe>