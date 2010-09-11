<?php
    if(!empty($_SERVER['PHP_AUTH_DIGEST'])) {
      $digest = $_SERVER['PHP_AUTH_DIGEST'];
      preg_match_all('@(username|nonce|uri|nc|cnonce|qop|response)'.'=[\'"]?([^\'",]+)@', $digest, $tmp);
      $data = array_combine($tmp[1], $tmp[2]);
      $auth_user = (count($data)==7) ? $data['username'] : false;
    } else {
      $auth_user = $_SERVER['PHP_AUTH_USER'];
    }
