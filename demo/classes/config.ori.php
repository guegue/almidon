<?php
#
# Nota: definir donde encontrar almidon?
#
#define ('ALMIDONDIR', '/usr/local/almidon/');
define ('DEBUG', true);

# Usar DB3 en vez de DB2 (FIXME: ambos deben unirse)
#define ('DB3', true);

# Idioma, por ahora solo "es" y "en"
define ('ALM_LANG','en');

# Time Zone
date_default_timezone_set('America/Managua');
 
# Constantes de conexion la base de datos
$admin_dsn = 'pgsql://almidondemo:secreto1@/almidondemo';
$public_dsn = 'pgsql://almidondemowww:secreto2@/almidondemo';

# Permite conectarse a la BD sin "almidonizarla"
$emergency_password = '21232f297a57a5a743894a0e4a801fc3';

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

# Constantes de valores (IDs) de las tablas
define('IDPAGINA', 1);
