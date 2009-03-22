<?php

// .htpasswd file functions
// Copyright (C) 2004,2005 Jarno Elonen <elonen@iki.fi>
//
// Redistribution and use in source and binary forms, with or without modification,
// are permitted provided that the following conditions are met:
//
// * Redistributions of source code must retain the above copyright notice, this
//   list of conditions and the following disclaimer.
// * Redistributions in binary form must reproduce the above copyright notice,
//   this list of conditions and the following disclaimer in the documentation
//   and/or other materials provided with the distribution.
// * The name of the author may not be used to endorse or promote products derived
//   from this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE AUTHOR ''AS IS'' AND ANY EXPRESS OR IMPLIED
// WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
// AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE AUTHOR
// BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
// DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
// LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
// ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
// NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
// EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
//
// Usage
// =====
//   require_once('htpasswd.inc.php');
//   $pass_array = load_htpasswd();
//
//   if ( test_htpasswd( $pass_array,  $user, $pass ))
//       print "Access granted."
//
//   $pass_array[$new_user] = rand_salt_crypt($new_pass);
//   save_htpasswd($pass_array);
//
//   $pass_array[$new_user2] = rand_salt_sha1($new_pass2);
//   save_htpasswd($pass_array);
//
// Thanks to Jonas Wagner for SHA1 support.


// Loads htpasswd file into an array of form
// Array( username => crypted_pass, ... )
function load_htpasswd()
{
  if ( !file_exists(HTPASSWDFILE))
      return Array();

  $res = Array();
  foreach(file(HTPASSWDFILE) as $l)
  {
    list($user, $pass) = split(':',$l);
    $pass = chop($pass);
    $res[] = array('usuario' => $user, 'passwd' => $pass);
  }
  return $res;
}

// Saves the array given by load_htpasswd
// Returns true on success, false on failure
function save_htpasswd( $pass_array , $mode=null)
{
  $result = true;

  ignore_user_abort(true);
  if($mode){
    $fp = fopen(HTPASSWDFILE, $mode);
  }else{
    $fp = fopen(HTPASSWDFILE, "a+");
  }
  if($pass_array){
    if (flock($fp, LOCK_EX))
    {
      while( list($u,$p) = each($pass_array))
        fputs($fp, "$u:$p\n");
      flock($fp, LOCK_UN); // release the lock
    }
    else
    {
      trigger_error("Could not save (lock) .htpasswd", E_USER_WARNING);
      $result = false;
    }
  }
  fclose($fp);
  ignore_user_abort(false);
  return $result;
}

// Generates a htpasswd compatible crypted password string.
function rand_salt_crypt( $pass )
{
  $salt = "";
  mt_srand((double)microtime()*1000000);
  for ($i=0; $i<CRYPT_SALT_LENGTH; $i++)
    $salt .= substr("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789./", mt_rand() & 63, 1);
  return crypt($pass, $salt);
}

// Generates a htpasswd compatible sha1 password hash
function rand_salt_sha1( $pass )
{
  mt_srand((double)microtime()*1000000);
  $salt = pack("CCCC", mt_rand(), mt_rand(), mt_rand(), mt_rand());
  return "{SSHA}" . base64_encode(pack("H*", sha1($pass . $salt)) . $salt);
}

// Generate a SHA1 password hash *without* salt
function non_salted_sha1( $pass )
{
  return "{SHA}" . base64_encode(pack("H*", sha1($pass)));
}

// Returns true if the user exists and the password matches, false otherwise
function test_htpasswd( $pass_array, $user, $pass )
{
  if ( !isset($pass_array[$user]))
      return False;
  $crypted = $pass_array[$user];

  // Determine the password type
  // TODO: Support for MD5 Passwords
  if ( substr($crypted, 0, 6) == "{SSHA}" )
  {
    $ohash = base64_decode(substr($crypted, 6));
    return substr($ohash, 0, 20) == pack("H*", sha1($pass . substr($ohash, 20)));
  }
  else if ( substr($crypted, 0, 5) == "{SHA}" )
    return (non_salted_sha1($pass) == $crypted);
  else
    return crypt( $pass, substr($crypted,0,CRYPT_SALT_LENGTH) ) == $crypted;
}
?>
