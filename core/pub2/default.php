<?php
/**
 * default.php
 *
 * rewrite magic: para generar pagina publica automaticamente (via mod_rewrite)
 * FIXME: magic is messy and not so useful. use objects! gallery.picasa.php...
 *
 * @copyright &copy; 2005-2008 Guegue Comunicaciones - guegue.com
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version $Id: default.php,v 2008032801 javier $
 * @package almidon
 */

require($_SERVER['DOCUMENT_ROOT'] . '/../classes/app.class.php');

# lee parametros, obj nunca esta vacio, tpl y php son opcionales
#$params = explode("/", $_SERVER['REQUEST_URI']);
if (isset($_GET['obj'])) $obj = strtolower(preg_replace("/[^A-Za-z0-9_]/", "", $_GET['obj']));
if (isset($_GET['id'])) $id = strtolower(preg_replace("/[^A-Za-z0-9_]/", "", $_GET['id']));
if (isset($_GET['tpl'])) $tpl = strtolower(preg_replace("/[^A-Za-z0-9_]/", "", $_GET['tpl']));
if (isset($_GET['php'])) $php = strtolower(preg_replace("/[^A-Za-z0-9_]/", "", $_GET['php']));
if (!empty($tpl)) $tpl = $smarty->template_dir.$tpl;
if (!empty($php)) $php = ROOTDIR.$php;
#if (!empty($obj)) $obj = $obj;

# intenta leer registro de pagina
$pagina = (defined('PAGINA')) ? PAGINA : 'pagina';

# si no hay objeto, es portada
if (empty($obj) || ($obj == 'defaultphp')) {
  if (file_exists('portada.php')) {
    require('portada.php');
  } else {
    $idpagina = (defined('IDPAGINA')) ? IDPAGINA : 'portada';
  }
}
$object = $obj.'Table';
if (!class_exists($object)) {
  $pagina_object = $pagina.'Table';
  if (class_exists($pagina_object)) {
    $pagina_data = new $pagina_object;
    if (!isset($idpagina)) $idpagina = $obj;
    $row = $pagina_data->readRecord($idpagina);
    if ($row) {
      $smarty->assign('row', $row);
      if (file_exists($smarty->template_dir . $idpagina . '.tpl'))
      $tpl = $idpagina . '.tpl';
    } else {
      error_log("FIXME: que hacer?");
    }
  }
}

# especificar el tpl
#if (empty($tpl)) $tpl = $php.'.tpl';
if (empty($tpl)) $tpl = $obj.'.tpl';
if (!file_exists($smarty->template_dir . $tpl))
  $tpl = ALMIDONDIR.'/tpl/default.tpl';

# cargar archivo extra php
if (empty($php)) $php = $obj.'.php';
if (file_exists($php))
  require($php);

# carga archivo extra si existe
if (file_exists('include.php'))
  require('include.php');

# carga el objeto: lee datos o dato
$object = $obj.'Table';
if (!empty($obj) && class_exists($object)) {
  $data = new $object;
  if (!empty($data->template))
    $tpl = $data->template;
  $rows = $data->readData();

  # si hay parametros, interpretarlos, ver detalle
  $data->readEnv();
  if (isset($data->request[$data->key]))
    $row = $data->readRecord();

  # si es galeria, carga fotos
  if ($obj == 'galeria' && !empty($row)) {
    $foto = new fotoTable();
    $fotos = $foto->readDataFilter("foto.idgaleria='".$row['idgaleria']."'");
    $smarty->assign('fotos', $fotos);
    $smarty->assign('galeria', $obj);
  }

  # si es foto de galeria, carga foto y galeria
  if ($obj == 'foto' && !empty($row)) {
    $fotos = $data->readDataFilter("foto.idgaleria='".$row['idgaleria']."'");
    $smarty->assign('fotos', $fotos);
    $smarty->assign('imagen', $row);
    $data = new galeriaTable();
    $row = $data->readRecord($row['idgaleria']);
    $obj = 'galeria';
  }

  # solo pone titulo si el objeto no es tipo "pagina"
  if ($obj != $pagina)
    $smarty->assign('title', $data->title);

  $smarty->assign('key', $data->key);
  $data->destroy();

  # finalmente asigna row
  if (isset($row))
    $smarty->assign('row', $row);

  # tabla con fotos y su key
  $smarty->assign('foto', 'foto');
  $smarty->assign('idfoto', 'idfoto');
}

# asgina variables a smarty
$smarty->assign('obj', $obj);
if (isset($rows)) $smarty->assign('rows', $rows);

# especifica display
$smarty->display($tpl);
