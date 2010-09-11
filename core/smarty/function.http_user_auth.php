<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.http_user_auth.php
 * Type:     function
 * Name:     http_user_auth
 * Purpose:  return the logged user with htpasswd or htdigest
 * -------------------------------------------------------------
 */
function smarty_function_http_user_auth($params, &$smarty)
{
  $val = null;
  if(!empty($_SERVER['PHP_AUTH_DIGEST'])) {
    $digest = $_SERVER['PHP_AUTH_DIGEST'];
    preg_match_all('@(username|nonce|uri|nc|cnonce|qop|response)'.'=[\'"]?([^\'",]+)@', $digest, $t);
    $data = array_combine($t[1], $t[2]);
    # all parts found?
    $val = (count($data)==7) ? $data['username'] : false;
  } else {
    $val = $_SERVER['PHP_AUTH_USER'];
  }
  if (!empty($params['name'])) {
    $smarty->assign($params['name'], $val);
  } else {
    return $val;
  }
}
?>
