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
 * @package almidon
 */

# Check username and password
function check_user($user, $pass) {
  $pass = md5($pass);
  $almuser = new almuserTable();
  $almuser->readEnv();
  $almuser_data = $almuser->readDataFilter("idalmuser='".$user."' AND password='".$pass."'");
  if(is_array($almuser_data)) {
    //Cargar Credenciales
    $_SESSION['credentials'] = get_credentials($almuser_data[0]['idalmuser']);
    $_SESSION['idalmuser'] = $almuser_data[0]['idalmuser'];
    $_SESSION['almuser'] = $almuser_data[0]['almuser'];
    return true;
  }else{
    return false;
  }
}

# Get user's credentials for each table
function get_credentials($idalmuser) {
  //Recorro Arreglo de tablas para chequear permisos
  $almuser = new almuserTable();
  $almuser->readEnv();
  $almtable = new almtableTable();
  $almtable->readEnv();
  $almtable_data = $almtable->readData();
  $almuser_record = $almuser->readRecord($idalmuser);
  $alm_tables = "/^(almtable|almuser|almaccess|almrole)$/";
  foreach ($almtable_data as $table) {
    switch ($almuser_record['idalmrole']) {
      case '': // Si no hay role por defecto, revisar personalizacion
        $almaccess = new almaccessTable();
        // role = 3 = ???
        $credentials = $almaccess->readDataFilter("almaccess.idalmuser=".$idalmuser." AND almaccess.idalmtable=".$table['idalmtable']." AND almaccess.idalmrole!=3");
        if(is_array($credentials)) {
          $i=0;
          foreach ($credentials as $key => $valor) {
            $credentialsql[$i] = $valor['idalmrole'];
            $i++;
          }
          $arrayCredentials[$table['idalmtable']] = $credentialsql;
        }
        break;
      case 'full': // total
        $arrayCredentials[$table['idalmtable']] = 'full';
        break;         
      case 'edit': // edicion
        if(!preg_match($alm_tables, $table['idalmtable']))
          $arrayCredentials[$table['idalmtable']] = 'edit';
        break;
      case 'delete': // Correccion, solo borrar
        if(!preg_match($alm_tables, $table['idalmtable']))
          $arrayCredentials[$table['idalmtable']] = 'delete';
        break;
      case 'read': // Guest, read-only...
        if(!preg_match($alm_tables, $table['idalmtable']))
          $arrayCredentials[$table['idalmtable']] = 'read';
        break;
      case 'deny': // No access
        # Nothing to do...
        break;
    }  
  } 
  return $arrayCredentials;
}
