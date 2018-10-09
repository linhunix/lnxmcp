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
 
 
 folder
  - mcp: Mcp base code
  - App: application code
  - mcp_test : Mcp test suite
  - mcp_module : Mcp extra module 
  - Tools: Many tools and scripts 


  config.json
  - app.level : loggin level 
  - app.def: application tag name 
  - app.PreloadOnly: true if need the sistem working only on preload config
  - [mail cause ] 
    - app.mail.smtp.host
    - app.mail.smtp.post
    - app.mail.smtp.user
    - app.mail.smtp.pass
    - app.mail.smtp.type
    - app.mail.pop3.host
    - app.mail.pop3.post
    - app.mail.pop3.user
    - app.mail.pop3.pass
    - app.mail.pop3.type
    - app.mail.file.log
    - app.mail.domaine
    - app.mail.from   
  
   
