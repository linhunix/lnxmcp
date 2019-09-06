    <form action='/lnxmcpapi' method='post' target='result' >
      <input name='type' type="hidden" value='mail' style='display:none;'>
      <input name='name' type="hidden" value='sendmail' style='display:none;'>
      <fieldset>
        <legend>Test Api</legend>
        <label>From</label>
        <input name='from' type="text" placeholder="Type from api">
        <label>To</label>
        <input name='to' type="text" placeholder="Type to api">
        <label>Subject</label>
        <input name='subject' type="text" placeholder="Type subject api">
        <label>Message</label>
        <textarea name='message'  style='width:100% !important;height:200px;' >
         Put Message 
        </textarea><br>
        <button type="submit" class="btn">Submit</button>
      </fieldset>
    </form>
    <iframe width='100%' name='result' style='background-color:white;width:100%;height:400px;'></iframe>