<?php
/**
 * users.php
 *
 * funciones de manejo de usuarios y roles
 *
 * function check_user: Chequea el usuario y el password. 
 * function get_credentials: Carga las credenciales del usuario logeado
 *
 * @copyright &copy; 2005-2009 Guegue Comunicaciones - guegue.com
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version users.php,v 20091013 Alejandro y Alexis
 * @package alm_idon
 */

# Check username and password
function check_user($user, $pass) {
  $pass = md5($pass);
  $alm_user = new alm_userTable();
  $alm_user->readEnv();
  $alm_user_data = $alm_user->readDataFilter("idalm_user='".$user."' AND password='".$pass."'");
  if(is_array($alm_user_data)) {
    //Cargar Credenciales
    $_SESSION['credentials'] = get_credentials($alm_user_data[0]['idalm_user']);
    $_SESSION['idalm_user'] = $alm_user_data[0]['idalm_user'];
    $_SESSION['alm_user'] = $alm_user_data[0]['alm_user'];
    return true;
  }else{
    return false;
  }
}

# Get user's credentials for each table
function get_credentials($idalm_user) {
  //Recorro Arreglo de tablas para chequear permisos
  $alm_user = new alm_userTable();
  $alm_user->readEnv();
  $alm_table = new alm_tableTable();
  $alm_table->readEnv();
  $alm_table_data = $alm_table->readData();
  $alm_user_record = $alm_user->readRecord($idalm_user);
  $alm_tables = "/^(alm_table|alm_user|alm_access|alm_role|alm_column)$/";
  foreach ($alm_table_data as $table) {
    switch ($alm_user_record['idalm_role']) {
      case '': // Si no hay role por defecto, revisar personalizacion
        $alm_access = new alm_accessTable();
        $credentials = $alm_access->readDataFilter("alm_access.idalm_user=".$idalm_user." AND alm_access.idalm_table=".$table['idalm_table']." AND alm_access.idalm_role!='deny'");
        if(is_array($credentials)) {
          $i=0;
          foreach ($credentials as $key => $valor) {
            $credentialsql[$i] = $valor['idalm_role'];
            $i++;
          }
          $arrayCredentials[$table['idalm_table']] = $credentialsql;
        }
        break;
      case 'full': // total
        $arrayCredentials[$table['idalm_table']] = 'full';
        break;         
      case 'edit': // edicion
        if(!preg_match($alm_tables, $table['idalm_table']))
          $arrayCredentials[$table['idalm_table']] = 'edit';
        break;
      case 'delete': // Correccion, solo borrar
        if(!preg_match($alm_tables, $table['idalm_table']))
          $arrayCredentials[$table['idalm_table']] = 'delete';
        break;
      case 'read': // Guest, read-only...
        if(!preg_match($alm_tables, $table['idalm_table']))
          $arrayCredentials[$table['idalm_table']] = 'read';
        break;
      case 'deny': // No access
        # Nothing to do...
        break;
    }  
  } 
  return $arrayCredentials;
}
