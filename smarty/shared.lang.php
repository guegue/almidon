<?php
# Choose the language base in the webbrowser
if(ALM_DETECT_LANG===true) {
  $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
  if(file_exists(dirname(__FILE__) . '/shared.lang_' . $lang . '.php'))  include dirname(__FILE__) . '/shared.lang_' . $lang . '.php';
} elseif(defined('ALM_LANG')) {
  if(file_exists(dirname(__FILE__) . '/shared.lang_' . ALM_LANG . '.php'))  include dirname(__FILE__) . '/shared.lang_' . ALM_LANG . '.php';
} else {
  setlocale(LC_TIME, "es_ES");
}
# End

if(!defined('ALM_EDIT_LB'))  define('ALM_EDIT_LB','Editar');
if(!defined('ALM_CAN_LB'))  define('ALM_CAN_LB','Cancelar');
if(!defined('ALM_SAVE_LB'))  define('ALM_SAVE_LB','Guardar');
if(!defined('ALM_NEXT_LB'))  define('ALM_NEXT_LB','Pr&oacute;ximo');
if(!defined('ALM_PREV_LB'))  define('ALM_PREV_LB','Previo');
if(!defined('ALM_OPT_LB'))  define('ALM_OPT_LB','Opciones');
if(!defined('ALM_MAX'))  define('ALM_MAX','maximizar');
if(!defined('ALM_ADD_LB')) define('ALM_ADD_LB','Agregar');
if(!defined('ALM_REC_LB'))  define('ALM_REC_LB','registros');
if(!defined('ALM_DEL_LB'))  define('ALM_DEL_LB','Borrar');
if(!defined('ALM_VIEW_LB'))  define('ALM_VIEW_LB','Ver');
if(!defined('ALM_AL_MSG_DEL'))  define('ALM_AL_MSG_DEL','Estas seguro de querer borrar este registro?');
if(!defined('ALM_ADMIN_TITLE'))  define('ALM_ADMIN_TITLE','Administración');
if(!defined('ALM_WCOME')) define('ALM_WCOME','Bienvenido! Conectado como:');
?>
