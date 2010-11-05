<?php
function checking_include($file) {
  $file = trim($file);
  // Is the path, according to the SO, absolute?
  $alm_abs_path = false;
  if ( strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' ) {
    $alm_abs_path = (bool)($file{1}==':');
  } else {
    $alm_abs_path = (bool)($file{0}=='/');
  }
  # if so, do this
  if ( $alm_abs_path ) {
    if ( file_exists( $file ) ) return true;
  } else {
  # if not, do this
    global $alm_inc_paths;
    if ( !isset($alm_inc_paths) ) {
      $alm_inc_paths = trim(get_include_path(),"\ \t\n\r\0\x0B" . PATH_SEPARATOR);
      $alm_inc_paths = explode(PATH_SEPARATOR,$alm_inc_paths);
      if ( !in_array('.',$alm_inc_paths) )
        $alm_inc_paths[] = '.';
    }
    foreach ( $alm_inc_paths as $alm_path ) {
      if ( file_exists($alm_path . '/' . $file ) ) return true;
    }
  }
  return false;
}

function alm_require($file) {
  if ( ADMIN !== true ) {
    require_once $file;
  } elseif ( checking_include($file) ) {
    require_once $file;
  } else {
    echo ALM_NOT_FOUND .' <span style="color:#035482;">' . $file . '</span>';
    exit;
  }
}
