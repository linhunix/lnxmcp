$configure = array(
    'packagename' => 'lnxmcp',
    'arch' => 'all',
    'version' => '`cat VERSION`',
    'maintainer' => 'Andrea Morello <andrea.morello@linhunix.com>',
    'description' => 'LinNote X All - Master Control Program',
    'url' => 'https://github.com/linhunix/lnxmcp',
    'packagetype' => 'rpm',
    'depends' => array(),

    'tmpdir' => ' / tmp',
    'templatedir' => 'pkgtemplates',
    'postinst' => '',
    'preinst' => '',
    'postrm' => '',
    'prerm' => '',
    'debconfconfig' => '', // only for debian: config file for debconf
    'debconftemplate' => '', // only for debian: template file for debconf
    'configfile' => '', // mark a file as configuration file
);

/* here you can define which files or directories should go where in the target system.
* You can use placeholders defined in your $configure array
* The syntax is dest => src so you don't have to repeat dest if you have lots
* of stuff to put in the same directory
* To prevent some files or directories from ending up in the package you can exclude
* them by prepending them with '- ' (see also example).
*
* Example:
*
* $filemapping = array(
*   '/opt/@PACKAGENAME@' => array(
*      'app/',
*   )
* )
*/
$filemapping = array(
    '/opt/lnxmcp' => array(
        '*',
        '- pkgtemplates',
        '- composer.lock',
        '- composer.json',
        '- webstat',
        '- Makefile'
    ),
);
