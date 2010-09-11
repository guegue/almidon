<?php
# Choose the language base in the webbrowser
if(defined('ALM_DETECT_LANG') && ALM_DETECT_LANG===true) {
  $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
  if(file_exists(dirname(__FILE__) . '/lang.' . $lang . '.php'))  include dirname(__FILE__) . '/lang.' . $lang . '.php';
} elseif(defined('ALM_LANG')) {
  if(file_exists(dirname(__FILE__) . '/lang.' . ALM_LANG . '.php'))  include dirname(__FILE__) . '/lang.' . ALM_LANG . '.php';
} else {
  include (dirname(__FILE__) . '/lang.es.php' );
}
