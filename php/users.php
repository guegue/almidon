<?php
/**
 * users.php
 *
 * function check_user-> Chequea el usuario y el password. 
 * 
 * function get_credentials->Carga las credenciales del usuario logeado
 *
 * @copyright &copy; 2005-2009 Guegue Comunicaciones - guegue.com
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version users.php,v 20091013 Alejandro y Alexis
 * @package almidon
 */


function check_user($user, $pass) {
  $pass = md5($pass);
  $userbd = new almuserTable();
  $userbd->readEnv();
  $arrayUser = $userbd->readDataFilter("almuser='".$user."' AND password='".$pass."'");
  if(is_array($arrayUser)) {
    //Cargar Credenciales
	$_SESSION['credentials'] = get_credentials($arrayUser[0]['idalmuser']);
    $_SESSION['user'] = $arrayUser[0]['idalmuser'];
    return true;
  }else{
    return false;
  }
}

//Obtiene el arreglo de credenciales por tabla de la base de datos
function get_credentials($iduser) {
  //Recorro Arreglo de tablas para chequear permisos
  $tablasbd = new almformTable();
  $tablasbd->readEnv();
  $arrayTablas = $tablasbd->readData();
  $userbd = new almuserTable();
  $userbd->readEnv();	  
  $user = $userbd->readRecord($iduser);
  foreach ($arrayTablas as $table) {
    switch ($user['idalmrole']) {
	  case ''://Personalizado
	    $rolbd = new almaccessTable();
        $credentials = $rolbd->readDataFilter("almaccess.idalmuser=".$iduser." AND almaccess.idalmform=".$table['idalmform']." AND almaccess.idalmrole!=3"); 
        if(is_array($credentials)) {
          $i=0;
          foreach ($credentials as $key => $valor) {
            $credentialsql[$i] = $valor['idalmrole'];
            $i++;
          }
          $arrayCredentials[$table['almform']] = $credentialsql;
        }
        break;
      case 1://superadmin
        $arrayCredentials[$table['almform']] = array(1);  
        break;  	    	  	   	
      case 2://Editores
        if($table['almform'] != 'almuser' && $table['almform'] != 'almform' &&  $table['almform'] != 'almaccess' && $table['almform'] != 'almtuser' && $table['almform'] != 'almrole')
          $arrayCredentials[$table['almform']] = array(2);
        break;	
      case 3://Correccion	
        if($table['almform'] != 'almuser' && $table['almform'] != 'almform' &&  $table['almform'] != 'almaccess' && $table['almform'] != 'almtuser' && $table['almform'] != 'almrole')
          $arrayCredentials[$table['almform']] = array(3);
        break;
      case 4://guest	
        if($table['almform'] != 'almuser' && $table['almform'] != 'almform' &&  $table['almform'] != 'almaccess' && $table['almform'] != 'almtuser' && $table['almform'] != 'almrole')
          $arrayCredentials[$table['almform']] = array(4);
        break;  		    	   	  
    }	
  } 
  return $arrayCredentials;
}

