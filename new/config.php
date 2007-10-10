<?
 
# Funciones que definen el comportamiento de PHP
 
setlocale(LC_TIME, "es_ES");
 
# Constantes de conexion la base de datos
 
if (ADMIN) define ('DSN', 'pgsql://example:pass@/example');
else define ('DSN', 'pgsql://example:pass@/example');
define ('DEBUG', true);
 
# Constantes del web y filesystem
 
define ('DOMAIN', 'example.org');
define ('ROOTDIR', '/www/' . DOMAIN);
define ('SQLLOG', ROOTDIR . '/logs/sql.log');
define ('LOGFILE', ROOTDIR . '/logs/cms.log');
define ('HOMEDIR', ROOTDIR .'/public_html');
define ('URL', 'http://www.' . DOMAIN);
define ('EMAIL', 'info@' . DOMAIN);

define ('MAXROWS', 5);
define ('MAXCOLS', 6);

# Constantes de valores (IDs) de las tablas
 
?>
