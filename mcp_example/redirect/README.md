## EXAMPLE OF PATH REDIRECT 

* run  
    > cd mcp_example/redirect
    > php -S localhost:6500

* on browser
    > http://localhost:6500/app.php

* results
    > http://www.linhunix.com

* on the folder cfg
    * file cfg/PathRewrite.json

    {
    "/app.php":"Location:http://www.linhunix.com/"
    }