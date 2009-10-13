<?php
// vim: set expandtab tabstop=2 shiftwidth=2 fdm=marker:
# Creates admin links
if (!isset($sectionlinks)&&!isset($adminlinks)) {
  # if sectionlinks and adminlinks are not settings
  $classes = get_declared_classes();
  foreach($classes as $key)
    if (stristr($key, 'table') && $key != 'table' && $key != 'tabledoublekey' && $key != 'Table' && $key != 'TableDoubleKey') {
      $table_object = new $key;
      // Modificacion hecho por lo antes comentado entre php5 y php4
      if(substr($key, 0, strpos($key, 'Table'))!==false) {
        $key = substr($key, 0, strpos($key, 'Table'));
      } else { $key = substr($key, 0, strpos($key, 'table')); }
      // End
      if(isset($_SESSION['credentials'][$key])) {
         $adminlinks[$key] = $table_object->title;
      }   
      // {{{ Para que es esto?
      if(isset($extralinks)){
         foreach($extralinks as $key=>$link){
           $adminlinks[$key] = $link;
         }
      }
      // }}} Para que es esto?
    }
  $adminlinks['logout'] = 'Salir'; //Link del Logout
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
        $objects = split(",",$val['objects']);
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
?>
