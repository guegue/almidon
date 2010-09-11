<?php
define('ADMIN',true);
require($_SERVER['DOCUMENT_ROOT'] . '/../classes/app.class.php');
if(defined('ALMIDONDIR')) require(ALMIDONDIR . '/php/htpasswd.inc.php');
else require('htpasswd.inc.php');

$params = explode('/', $_SERVER['REQUEST_URI']);
$object = $params[2];
if (strpos($object, '?')) {
  $object = substr($object, 0, strpos($object, '?'));
  define('SELF', substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'],'?')));
} else {
  define('SELF', $_SERVER['REQUEST_URI']);
}

switch($_REQUEST['action']){
 case 'add':
	$new[$_POST['usuario']] = rand_salt_crypt($_POST['passwd']);
	save_htpasswd($new);
	break;
 case 'save':
	$rows = load_htpasswd();
        for($i=0;$i<count($rows);$i++){
	  if($i==0){
            if($rows[$i]['usuario']==$_POST['usuario']){
	      $new[$_POST['usuario']] = rand_salt_crypt($_POST['passwd']);
	    }else{
	      $new[$rows[$i]['usuario']] = $rows[$i]['passwd'];
            } 
          }else{
            if($rows[$i]['usuario']==$_POST['usuario']){
	      $new[$_POST['usuario']] = rand_salt_crypt($_POST['passwd']);
	    }else{
	      $new[$rows[$i]['usuario']] = $rows[$i]['passwd'];
            }
	  }
 	}
	save_htpasswd($new, "w+");
	break;
 case 'delete':
	$rows = load_htpasswd();
	$isCreat = false;
        for($i=0;$i<count($rows);$i++){
           if($rows[$i]['usuario']!=$_GET['usuario']){
	      $new[$rows[$i]['usuario']] = $rows[$i]['passwd'];
	   }
        }
	save_htpasswd($new, "w+");
	break;
}

$smarty->assign('title', 'Usuarios');
$smarty->caching = false;
$tpl = 'normal';

// dd
$dd['usuario'] = array('name' => 'usuario', 'type '=> 'varchar', 'size' => 20, 'references' => null, 'label' => 'Nombre de Usuario', 'extra' => null);
$dd['passwd'] = array('name' => 'passwd', 'type '=> 'varchar', 'size' => 16, 'references' => null, 'label' => 'Contrase&#241;a', 'extra' => null);
$smarty->assign('dd', $dd);

// rows
$rows = load_htpasswd();
$smarty->assign('rows', $rows);

// num_rows
$smarty->assign('num_rows', 20);
$smarty->assign('num_records', count($rows));

// key
$smarty->assign('key', 'usuario');

$tpl = 'usuario';
if (file_exists(ROOTDIR.'/templates/admin/header.tpl')) {
  $smarty->assign('header',ROOTDIR."/templates/admin/header.tpl");
  $smarty->assign('footer',ROOTDIR."/templates/admin/footer.tpl");
} else {
  $smarty->assign('header',ALMIDONDIR.'/tpl/header.tpl');
  $smarty->assign('footer', ALMIDONDIR . '/tpl/footer.tpl');
}

$smarty->display(ALMIDONDIR.'/tpl/'.$tpl.'.tpl');
?>
