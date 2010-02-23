<?php
// vim: set expandtab tabstop=2 shiftwidth=2 fdm=marker:
/**
 * pic.php
 *
 * resize de imagens via /cms/pic/SIZE/SUBDIR/FILENAME
 *
 * @copyright &copy; 2005-2008 Guegue Comunicaciones - guegue.com
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version $Id: pic.php,v 2009111901 javier $
 * @package almidon
 */

header('Content-type: image/jpeg');

require($_SERVER['DOCUMENT_ROOT'] . '/../classes/app.class.php');

# Gets parameters
$params = explode('/', $_SERVER['REQUEST_URI']);
$object = $params[count($params)-1];
$size = $params[3];
$subdir = $params[4];
$filename = $params[5];

# Gets image info
$fullpath = ROOTDIR . '/files/' . $subdir . '/' . urldecode($filename);
$getimagesizeinfo = getimagesize($fullpath);
$mime = $getimagesizeinfo['mime'];
$ImageCreateFromFunction = array(
  1 => 'ImageCreateFromGIF',
  2 => 'ImageCreateFromJPEG',
  3 => 'ImageCreateFromPNG',
  15 => 'ImageCreateFromWBMP',
);
$width = $getimagesizeinfo[0];
$height = $getimagesizeinfo[1];

# Sets new width and height
if (preg_match('/(.+)x(.+)/', $size)) {
  list($newwidth,$newheight) = preg_split('/x/', $size);
} else {
  $newwidth = $size;
  $percentage = $newwidth / $width;
  $newheight = $height * $percentage;
}

# Sets cache vars
$cache_dir = ROOTDIR . '/cache/' . $subdir;
$cache_file = $cache_dir . '/' . $size . '_' . $filename;
if (!file_exists($cache_dir))
  mkdir  ($cache_dir, 0777, true);
$time = filemtime($fullpath);
$cached = file_exists($cache_file);
if ($cached) {
  $cache_time = filemtime($cache_file);
  if ($time > $cache_time)
    $cached = false;
}

# Resize...
if (!$cached) {
  $thumb = imagecreatetruecolor($newwidth, $newheight);
  $ImageCreateFromFunctionName = $ImageCreateFromFunction[$getimagesizeinfo[2]];
  $source = $ImageCreateFromFunctionName($fullpath);
  imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
  imagejpeg($thumb, null, 100);
  imagejpeg($thumb, $cache_file, 100);
  imagedestroy($thumb);
  imagedestroy($source);
  #error_log("Not using cache");
# Use cached image file
} else {
  #error_log("Using cache: $cache_file");
  readfile($cache_file);
}
