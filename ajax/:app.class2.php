<?
#session_start();
require('ecommerce.class.php');
require_once('config.php');
require_once('/www/cms/php/db3.class.php');
require_once('Smarty/Smarty.class.php');

function getArgs() {
  $params = explode("/", $_SERVER['PATH_INFO']);
  for($i = 1; $i < sizeof($params); $i++)
    $args[$i] = $params[$i];
  return $args;
}

$smarty = new Smarty;
$smarty->template_dir = ROOTDIR . '/templates/';
$smarty->compile_dir = ROOTDIR . '/templates_c/';
$smarty->config_dir = ROOTDIR . '/configs/';
$smarty->cache_dir = ROOTDIR . '/cache/';

$str = substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],'/'));
$section = substr($str,strrpos($str,'/')+1);

if(($section==DOMAIN)||($section=='secure'))
  $section = 'inicio';

$sectionlinks['general'] = array('label'=>'E-end', 'index'=>'');
$sectionlinks['blog'] = array('label'=>'Blog', 'index'=>'');
$sectionlinks['multimedia'] = array('label'=>'Recursos Multimedia', 'index'=>'');
$sectionlinks['cartelera'] = array('label'=>'Cinematograf&iacute;a', 'index'=>'');
$sectionlinks['comercio'] = array('label'=>'E-commerce', 'index'=>'');
$sectionlinks['config'] = array('label'=>'Configuraci&oacute;n', 'index'=>'');

//if ($_SERVER['SERVER_NAME'] == 'secure.guegue.com') {
  //$adminlinks['pagina.php'] = 'Paginas';
  switch ($section){
    case 'config':
      $adminlinks['seccion.php'] = 'Secciones';
      //$adminlinks['subseccion.php'] = 'Sub Secciones';
      $adminlinks['fuente.php'] = 'Fuentes';
      $adminlinks['categoria.php'] = 'Categoria';
      $adminlinks['motivo.php'] = 'Motivo Correcion';
      $adminlinks['tipo.php'] = 'Tipo';
      $adminlinks['tratamiento.php'] = 'Tratamiento';
      $adminlinks['usuario.php'] = 'Usuario';
      $adminlinks['boletin.php'] = 'Boletin';
      $adminlinks['configuracion.php'] = 'Variables de Config.';
      $adminlinks['blog.php'] = 'Categoria Blog';
      $adminlinks['red.php'] = 'Para Compartir';
      break;
    case 'blog':
      $adminlinks['autorblog.php'] = 'Autores';
      $adminlinks['seccionblog.php'] = 'Secciones';
      $adminlinks['entrada.php'] = 'Entradas';
      $adminlinks['comentarioblog.php'] = 'Comentarios';
      break;
    case 'general':
      $adminlinks['autor.php'] = 'Autores';
      $adminlinks['encuesta.php'] = 'Encuesta';
      //$adminlinks['opcion.php'] = 'Opciones';
      $adminlinks['articulo.php'] = 'Articulo';
      //$adminlinks['articulorel.php'] = 'Articulos Relacionados';
      $adminlinks['comentario.php'] = 'Comentarios';
      $adminlinks['correccion.php'] = 'Correccion(es)';
      $adminlinks['hoy.php'] = 'Seccion Hoy';
      $adminlinks['nota.php'] = 'Nota';
      break;
    case 'cartelera':
      $adminlinks['cinema.php'] = 'Cinemas';
      $adminlinks['pelicula.php'] = 'Peliculas';
      $adminlinks['cartelera.php'] = 'Cartelera';
      break;
    case 'comercio':
      $adminlinks['producto.php'] = 'Producto';
      $adminlinks['pedido.php'] = 'Pedido';
      $adminlinks['pedidoproducto.php'] = 'Pedido - producto';
      $adminlinks['tipotarjeta.php'] = 'Tipos de tarejeta';
      break;
    case 'multimedia':
      $adminlinks['video.php'] = 'Video';
      $adminlinks['audio.php'] = 'Audio';
      $adminlinks['foto.php'] = 'Foto';
      $adminlinks['galeria.php'] = 'Galeria';
      break; 
    default:
      $adminlinks = null;
      break;
  }
  
  $smarty->assign('section_actual', $section);
  $smarty->assign('sectionlinks', $sectionlinks);
  $smarty->assign('adminlinks', $adminlinks);
//}

require('tables.class.php');
require('extra.class.php');

if(!defined('ADMIN')) {
  $seccion = new seccionTable();
  $smarty->assign('secciones', $seccion->readDataFilter('menu'));

  // Fecha y hora de ultima actualizacion
  $row = $seccion->readDataSQL('select date_trunc(\'day\', fechapublicacion) AS fecha, fechapublicacion AS fechatiempo, \''.date("Y-m-d H:i:s").'\' - fechapublicacion AS ago from articulo where fechapublicacion < \''.date("Y-m-d H:i:s").'\' order by fechapublicacion desc limit 1;');
  $row[0]['ago'] = substr($row[0]['ago'],0,-3);
  $strday = (string)$row[0]['ago'];
  $row[0]['ago'] = eregi_replace('days','d&iacute;as', $strday);
  $row[0]['ago'] = eregi_replace('day','d&iacute;a', $strday);
  $smarty->assign('lastupdate',$row[0]);
  if(date("Y-m-d 00:00:00")==$row[0]['fecha'])
    $smarty->assign('updateistoday',true);
  else
    $smarty->assign('updateistoday',false);
  // Las variabes de configuracion las vuelvo constantes
  $data = new configuracionTable();
  $rows = $data->readData();
  foreach($rows as $row) 
    define($row['idconfig'],$row['config']);
  unset($data);
}
?>
