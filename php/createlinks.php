<?php
// vim: set expandtab tabstop=2 shiftwidth=2 fdm=marker:
# Creates admin links
if (!isset($sectionlinks)&&!isset($adminlinks)) {
  # if sectionlinks and adminlinks are not settings
  $classes = get_declared_classes();
  foreach($classes as $key) {
    if (stristr($key, 'table') && $key != 'table' && $key != 'tabledoublekey' && $key != 'Table' && $key != 'TableDoubleKey') {
      $table_object = new $key;

      # Modificacion hecho por DiFeReNcIa entre php5 y php4
      if(substr($key, 0, strpos($key, 'Table'))!==false)
        $key = substr($key, 0, strpos($key, 'Table'));
      else
        $key = substr($key, 0, strpos($key, 'table'));

      # Solo agrega link si el usuario tiene acceso a esa tabla o si es admin
      if(isset($_SESSION['credentials'][$key]) || $_SESSION['idalm_user'] === 'admin' )
         $adminlinks[$key] = $table_object->title;

      # Para agregar links adicionales, lo que nos de la gana
      if(isset($extralinks)) {
         foreach($extralinks as $key=>$link)
           $adminlinks[$key] = $link;
      }
    }
  }
  # Links adicionales
  if ($_SESSION['idalm_user'] === 'admin' || $_SERVER['REMOTE_ADDR'] === '127.0.0.1')
    $adminlinks['setup'] = ALM_SETUP;
  $adminlinks['logout'] = ALM_LOGOUT;
  $smarty->assign('adminlinks', $adminlinks);
} elseif(isset($sectionlinks)) {
  # If sectionlinks is defined 
  $params = explode('/', $_SERVER['REQUEST_URI']);
  if((strrpos($_SERVER['REQUEST_URI'],'/')+1) != strlen($_SERVER['REQUEST_URI'])) {
    $table = $params[count($params)-1];
  } else {
    $section = $params[count($params)-2];
  }
  if(!empty($table)&&strpos($table, '?'))  $table = substr($table, 0, strpos($table, '?'));
  if(!empty($table)&&strrpos($table, '.')!==false) $table = substr($table, 0, strrpos($table, '.'));
  if($sectionlinks) {
    foreach($sectionlinks as $key => $val) {
      if(!empty($val['objects'])) {
        $objects = preg_split('/,/',$val['objects']);
        $sectionlinks[$key]['adminlinks'] = array();
        foreach($objects as $object) {
          $class = $object.'Table';
          $$class = new $class;
          $sectionlinks[$key]['adminlinks'][$object] = $$class->title;
        }
        if(array_key_exists($table,$sectionlinks[$key]['adminlinks'])==true)  $section = $key;
      }
    }
  }
  $smarty->assign('cur_section', $section);
  $smarty->assign('sectionlinks', $sectionlinks);
  if(count($sectionlinks) > 1) {
    $smarty->assign('adminlinks', $sectionlinks[$section]['adminlinks']);
  } else {
    list($part) = array_values($sectionlinks);
    $smarty->assign('adminlinks', $part['adminlinks']);
  }

  //$smarty->assign('credentials', $_SESSION[]);
  unset($params);
  unset($section);
  unset($sectionlinks);
}
