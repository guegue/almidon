<?
 
# Funciones que definen el comportamiento de PHP
 
setlocale(LC_TIME, "es_ES");
 
# Constantes de conexion la base de datos
 
if (ADMIN) define ('DSN', 'pgsql://example:pass@/example');
else define ('DSN', 'pgsql://examplewww:pass@/example');
define ('DEBUG', true);
 
# Constantes del web y filesystem
 
$configdir = dirname(__FILE__);
$rootdir = substr($configdir, 0, strrpos($configdir,'/'));
define ('DOMAIN', 'example.org');
define ('ROOTDIR', $rootdir);
# o
# define ('ROOTDIR', '/www/' . DOMAIN);
define ('SQLLOG', ROOTDIR . '/logs/sql.log');
define ('LOGFILE', ROOTDIR . '/logs/cms.log');
define ('HOMEDIR', ROOTDIR .'/public_html');
define ('URL', 'http://www.' . DOMAIN);
define ('EMAIL', 'info@' . DOMAIN);
# Directorio donde se almacenan los thumbs que se crearan
# define ('PIXDIR', HOMEDIR.'/pix');

# Valores de configuración de Almidon
# Determina las etiquetas html que estan permitidas, si no se define se usara un valor por defecto
#define('ALM_ALLOW_TAGS', '<br/><br><p><h1><h2><h3><b><i><div><span><img1><img2><img3><strong><li><ul><ol><table><tbody><tr><td><font><a><sup><object><param><embed><hr><hr /><hr/>');
# Establece el path/ruta de la instalación de almidon
#define('ALMIDONDIR','/path-to-alm');
# Calidad de las imagenes, sino se estable se utilizara 85
#define('IMG_QUALITY',95);
# Indica que se utilizara DB3, si el valor es true, en vez de DB2.
#define ('DB3', true);

define ('MAXROWS', 5);
define ('MAXCOLS', 6);

# Constantes de valores (IDs) de las tablas
define('IDPAGINA', 1);
?>
