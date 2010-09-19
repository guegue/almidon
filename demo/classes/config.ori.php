<?php
/**
 * config.php, configuracion especifica de almidon para cada sitio web
 * @package almidon
 */

/**
* DEBUG: Enviar lo mas posible de errores al log
*/
define ('DEBUG', true);
/**
* Debe grabar comandos SQL en bitacora?
*/
if (!defined('ALM_SQL_DEBUG')) define('ALM_SQL_DEBUG', true);

# Usar DB3 en vez de DB2 (FIXME: ambos deben unirse)
#define ('DB3', true);

/**
* ALMD_LANG - Idioma, por ahora solo "es" y "en"
*/
define ('ALM_LANG','en');
# Time Zone
date_default_timezone_set('America/Managua');
 
# Constantes de conexion la base de datos
$admin_dsn = 'pgsql://almidondemo:********@/almidondemo';
$public_dsn = 'pgsql://almidondemowww:********@/almidondemo';

# Permite conectarse a la BD sin "almidonizarla"
$emergency_password = '00000000000000000000000000000000';

/**
* ALM_SYNC - Aplicar cambios automaticos a BD
*/
define('ALM_SYNC', true);

/**
* ADMIN: Estamos en modo administrador?
*/
if (!defined('ADMIN')) define('ADMIN', false);

/**
* DSN: Cadena de conexion a la base de datos
*/
if (ADMIN===true) define ('DSN', $admin_dsn);
/**
* @ignore - mismo DSN
*/
else define ('DSN', $public_dsn);
 
# Constantes del web y filesystem
$configdir = dirname(__FILE__);
$rootdir = substr($configdir, 0, strrpos($configdir,'/'));

/**
* ROOTDIR: Directorio del core de almidon
*/
define ('ROOTDIR', $rootdir);
/**
* ROOTDIR: Donde esta Almidon?
*/
#define ('ALMIDONDIR', '/usr/local/almidon/');
/**
* DOMAIN: Dominio de este sitio
*/
define ('DOMAIN', 'local.almidon.org');
/**
* Log para guardar comandos SQL
*/
define ('SQLLOG', ROOTDIR . '/logs/sql.log');
/**
* Log para varios
*/
define ('LOGFILE', ROOTDIR . '/logs/cms.log');
/**
* Directorio casa del web publico
*/
define ('HOMEDIR', ROOTDIR .'/public_html');
/**
* Directorio donde guardar imágenes generadas
*/
define ('PIXDIR', HOMEDIR.'/pix');
/**
* URL de este sitio web
*/
define ('URL', 'http://' . DOMAIN);
/**
* Email a usar por defecto
*/
define ('EMAIL', 'info@' . DOMAIN);
/**
* Número máximo de filas en datagrids y similares
*/
define ('MAXROWS', 5);
/**
* Número máximo de columns en datagrids y similares
*/
define ('MAXCOLS', 6);
/**
* ALM_CACHE: Habilitar SQL Cache?
*/
define ('ALM_CACHE', true);
/**
* ALM_CACHE_TIME: Tiempo en que expira el SQL Cache
*/
define ('ALM_CACHE_TIME', 60*5);
/**
* TinyMCE comprimido?
* By default it is true, set it false if you enable zlib compression in php.ini
*/
#define ('ALM_TINY_COMPRESSOR',true);

/**
* id de pagina a mostrar en portada si no se define ninguna
*/
define('IDPAGINA', 1);

/**
* Que tags HTML permite almidon?
*/
if (!defined('ALM_ALLOW_TAGS')) define('ALM_ALLOW_TAGS', '<br/><br><p><h1><h2><h3><b><i><div><span><img><img1><img2><img3><img4><strong><li><ul><ol><table><tbody><tr><td><font><a><sup><object><param><embed><hr><hr /><hr/>');

/**
* CDN_USERNAME: Username de conexion a Content Delivery Network
*/
define('CDN_USERNAME', 'demo');
/**
* CDN_APIKEY: KEY de conexion a Content Delivery Network
*/
define('CDN_APIKEY', '00000000000000000000000000000000');
/**
* CDN_REPO: Repositorio del Content Delivery Network
*/
define('CDN_REPO', 'demo.almidon.org');
/**
* CDN_URL: URL del Content Delivery Network
*/
define('CDN_URL', 'http://00000000.cdn.example.com/');
