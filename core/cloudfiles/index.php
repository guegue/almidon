<?php

if ($_POST['action']='upload') {
  $username = 'bob';
  $api_key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
  $container = 'example.com';

  $auth = new CF_Authentication($username, $api_key);
  $auth->authenticate();
  $conn = new CF_Connection($auth);
  $files = $conn->create_container(container);

  $filename = 'example.mp3';
  $localname = '/var/www/example.com/test.mp3'

  $afile = $files->create_object($filename);
  $size = (float) sprintf("%u", filesize($localname));
  $fp = open($localname, "r");
  $afile->write($fp, $size);
  $afile->load_from_filename($localname);
  $uri = $files->make_public();
  print $files->public_uri();
}
?>

<html>
<head>
<title>Upload file to CDN</titel>
</head>
<body>
<form>
 <input type="hidden" name="action" value="upload"/>
  File to upload: <input type="file" name="webname"/>
  <input type="submit" value="Upload"/>
</form>
</body>
</html>
