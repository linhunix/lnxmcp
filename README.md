Master Control Program
=========================
Is a simple MCP/MVC framework with out compose or other features an compatible with php 5.3 and more 

It's a reflection framework and not a solid framework 
  It is implement:
 - simple log service 
 - simple mail service 
 - simple db pdo serice 
 - simple data cache 
 - simple shell command service 
 
 for more info [[Wiki GitHub Pages|https://github.com/linhunix/lnxmcp/wiki/]] 

 
**LNX** (LinHUniX) **MCP** (Master Control Program) is a share code with free license.
 
Develop and Maintain by **LinHUniX Ltd**  - 2002/2020  - Author is **Andrea Morello (LinHUniX)**

> is a parts of the LN4 Note projects 


  
   
tag to init row:

    <? /*LNXMCP-INIT*/ if (function_exists(lnxmcp)==false){ include $_SERVER["DOCUMENT_ROOT"]."/app.php" ; }; lnxmcp()->imhere(); /*LNXMCP-END*/ ?>