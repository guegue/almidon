<?php

class livefillcombo {
/**
 * PHP5 requires a constructor
 */
  function livefillcombo() {
  }
  
  /*
    Return array
  */
  function filter_data($parent, $son, $id, $idselected=null) {
    $result = array();
    include("/www/".$_SERVER['SERVER_NAME']."/classes/app.class.php");
    //  Nombre de la tabla padre
    $objParent = substr($parent,2);
    //  Nombre de la tabla hija
    $objSon = substr($son,2);
    //  Nombre de la clase
    $ot = $objSon."Table";
    $obj = new $ot;
    $rows = $obj->readDataFilter("$objParent.id$objParent = $id");
    $result[0] = $son;
    for($i=0;$i<count($rows);$i++) {
      $result[$i+1][0] = $rows[$i]["id$objSon"];
      $result[$i+1][1] = $rows[$i]["$objSon"];
      if($rows[$i]["id$objSon"]==$idselected)  $result[$i+1][2] = true;
      else  $result[$i+1][2] = false;
    }
    return $result;
  }
   		
}

/*$o = new livefillcombo();
$o->filter_data('idcinema','idsala',2);*/
?>
