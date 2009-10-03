<?php

function check_user($user, $pass) {
	 $pass = md5($pass);
	 $userbd = new almuserTable();
	 $userbd->readEnv();
	 $arrayUser = $userbd->readDataFilter("almuser='".$user."' AND password='".$pass."'");
	 if(is_array($arrayUser)) {
	 	//Cargar Credenciales
	 	$_SESSION['credentials'] = get_credentials($arrayUser[0]['idalmuser']);
        $_SESSION['user'] = $arrayUser[0]['idalmuser'];
	 }
}

//Obtiene el arreglo de credenciales por tabla de la base de datos
function get_credentials($iduser) {
	  //Recorro Arreglo de tablas para chequear permisos
	  $tablasbd = new almformTable();
	  $tablasbd->readEnv();
	  $arrayTablas = $tablasbd->readData();
	  foreach ($arrayTablas as $table) {
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
	  } 
     return $arrayCredentials;
	  
}
