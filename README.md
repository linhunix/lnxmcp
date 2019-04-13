Master Control Program
=========================
Is a simple MCP/MVC framework with out compose or other features an compatible with php 5.3 and more 

### Why MCP? ###
The big difference of a standard **mvc** and **mvp** is the presence obbligatories of a central code.
and your functionality is an extension of it :
- Slim 
- Laravel

        Web >> MVP/MVC >> Your Code (as an extension of MVP/MVC);

A MCP is a layer, whe you ad this on your code, then is automatic estended with this features

        Web >> Your Code << MCP >> New Features;
    
That make the developper and the designer in a very confident solution :
- The designe don't need to know the php or the lnxmcp code but only the tag 
- the backender developper can use the lnxmcp code as library or as estension
 
 A similar example of MCP is Jquery 

### Don't Need Composer but support it ###
lnxmcp is born to is indipendent with composer, if is present use it 
if is not present is a substitute of it and the autoloader 

### work with php 5.3 and upper ##
the logic is to be a manager of older and new code 

### support function and class namespace ###
The logic inside support both nmespace function and class

### It's a reflection framework and not a solid framework ###

  It is implement:
 - Data cache 
 - Log service 
 - Mail service 
 - Debug Serices
 - BlackBox Logic
 - Remote Api Proxy 
 - Array's enrichment
 - Html tag Converter
 - Model View Controller 
 - Lazy Component Loader 
 - Shell command service 
 - Multi Site Configuration
 - Function Sequence Manager
 - Multi Language Integration
 - Internal Check and Test Suite
 - Namespace and library load manager
 - Category and Http Routing Management
 - multi db pdo serice (Sql lite and MySql) 
 
### Why GPL v3?

**LNX** (LinHUniX) **MCP** (Master Control Program) is a share code with free license.
Develop and Maintain by **LinHUniX Ltd**  - 2008/2020  - Author is **Andrea Morello (LinHUniX)**
Is a parts of the LN4 Note projects started on 2008 (ln4.it and ln4.app)

The lnxmcp (php/phar library) and lnxfea (js/angular2 library) are the common sdk to undestood
the logic of LN4 Java Backend and create around it the functionality need to extend.

Java LN4 Backend and Middle Ware >> Json Api >> lnxfea/lnxmcp fronted  >> Site or application

**For more info [[ https://github.com/linhunix/lnxmcp/wiki/ | Wiki GitHub Pages ]]**
  
   
tag to init row:

    <? /*LNXMCP-INIT*/ if (function_exists("lnxmcp")==false){ include $_SERVER["DOCUMENT_ROOT"]."/app.php" ; }; lnxmcp()->imhere(); /*LNXMCP-END*/ ?>
