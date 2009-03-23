<?php
 
# Funciones que definen el comportamiento de PHP
 
setlocale(LC_TIME, "es_ES");
 
# Constantes de conexion la base de datos
 
if (!defined('ADMIN')) define('ADMIN', false);
if (ADMIN===true) define ('DSN', 'pgsql://almidondemo:secreto1@/almidondemo');
else define ('DSN', 'pgsql://almidondemowww:secreto2@/almidondemo');

define ('DEBUG', true);
 
# Constantes del web y filesystem

$configdir = dirname(__FILE__);
$rootdir = substr($configdir, 0, strrpos($configdir,'/'));
define ('ROOTDIR', $rootdir);
define ('DOMAIN', 'demo.almidon.org');
#define ('ALMIDONDIR', '/usr/local/almidon/');
define ('ALMIDONDIR', ROOTDIR . '/..');
define ('SQLLOG', ROOTDIR . '/logs/sql.log');
define ('LOGFILE', ROOTDIR . '/logs/cms.log');
define ('HOMEDIR', ROOTDIR .'/public_html');
define ('URL', 'http://www.' . DOMAIN);
define ('EMAIL', 'info@' . DOMAIN);
define ('PIXDIR', HOMEDIR.'/pix');

define ('MAXROWS', 5);
define ('MAXCOLS', 6);

# Constantes de valores (IDs) de las tablas

define('IDPAGINA', 1);
