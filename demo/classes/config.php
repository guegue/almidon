<?
 
# Funciones que definen el comportamiento de PHP
 
setlocale(LC_TIME, "es_ES");
 
# Constantes de conexion la base de datos
 
if (ADMIN===true) define ('DSN', 'pgsql://almidondemo:nituni2@/almidondemo');
else define ('DSN', 'pgsql://almidondemowww:yosi@/almidondemo');
define ('DEBUG', true);
 
# Constantes del web y filesystem

$configdir = dirname(__FILE__);
$rootdir = substr($configdir, 0, strrpos($configdir,'/'));
define ('ROOTDIR', $rootdir);
define ('DOMAIN', 'demo.almidon.org');
define ('ALMIDONDIR', '/www/demo.almidon.org/svn/trunk');
define ('SQLLOG', ROOTDIR . '/logs/sql.log');
define ('LOGFILE', ROOTDIR . '/logs/cms.log');
define ('HOMEDIR', ROOTDIR .'/public_html');
define ('URL', 'http://www.' . DOMAIN);
define ('EMAIL', 'info@' . DOMAIN);

define ('MAXROWS', 5);
define ('MAXCOLS', 6);

# Constantes de valores (IDs) de las tablas

define('IDPAGINA', 1);
 
?>
