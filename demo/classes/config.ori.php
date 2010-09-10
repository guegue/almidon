<?php
/**
 * config.php, configuracion especifica de almidon para cada sitio web
 * @package almidon
 */

# Definir donde encontrar almidon?
#define ('ALMIDONDIR', '/usr/local/almidon/');
define ('DEBUG', true);

# Usar DB3 en vez de DB2 (FIXME: ambos deben unirse)
#define ('DB3', true);

# Idioma, por ahora solo "es" y "en"
define ('ALM_LANG','en');

# Time Zone
date_default_timezone_set('America/Managua');
 
# Constantes de conexion la base de datos
$admin_dsn = 'pgsql://almidondemo:********@/almidondemo';
$public_dsn = 'pgsql://almidondemowww:********@/almidondemo';

# Permite conectarse a la BD sin "almidonizarla"
$emergency_password = '00000000000000000000000000000000';

# Aplicar cambios automaticos a BD
define('ALM_SYNC', true);

if (!defined('ADMIN')) define('ADMIN', false);
if (ADMIN===true) define ('DSN', $admin_dsn);
else define ('DSN', $public_dsn);
 
# Constantes del web y filesystem
$configdir = dirname(__FILE__);
$rootdir = substr($configdir, 0, strrpos($configdir,'/'));
define ('ROOTDIR', $rootdir);
define ('DOMAIN', 'local.almidon.org');
define ('SQLLOG', ROOTDIR . '/logs/sql.log');
define ('LOGFILE', ROOTDIR . '/logs/cms.log');
define ('HOMEDIR', ROOTDIR .'/public_html');
# define ('URL', 'http://www.' . DOMAIN);
define ('URL', 'http://' . DOMAIN);
define ('EMAIL', 'info@' . DOMAIN);
define ('PIXDIR', HOMEDIR.'/pix');
define ('MAXROWS', 5);
define ('MAXCOLS', 6);
define ('ALM_CACHE_TIME', 60*5);
define ('ALM_CACHE', true);

# Constantes de valores (IDs) de las tablas
define('IDPAGINA', 1);

# Conexion al CDN
define('CDN_USERNAME', 'demo');
define('CDN_APIKEY', '00000000000000000000000000000000');
define('CDN_REPO', 'demo.almidon.org');
define('CDN_URL', 'http://00000000.cdn.example.com/');
