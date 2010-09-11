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
if(!file_exists($fullpath)) $fullpath = ALMIDONDIR . '/pub/img/404.png';
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
if (preg_match('/(\d+)x(\d+)x(.+)/', $size)) {
  list($newwidth,$newheight,$options) = preg_split('/x/', $size);
} elseif (preg_match('/(\d+)x(\d+)/', $size)) {
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
  $ImageCreateFromFunctionName = $ImageCreateFromFunction[$getimagesizeinfo[2]];
  $source = $ImageCreateFromFunctionName($fullpath);
  # Crop if needed
  if (isset($options) && $options === 'C') {
    $ratio = $width/$height;
    $newratio = $newwidth/$newheight;
    if ($newratio > $ratio) {
      $tmpheight = $newwidth/$ratio;
      $tmpwidth = $newwidth;
    } else {
      $tmpwidth = $newheight*$ratio;
      $tmpheight = $newheight;
    }
    $mid = array($tmpwidth/2, $tmpheight/2, $newwidth/2, $newheight/2);
    $tmp = imagecreatetruecolor(round($tmpwidth), round($tmpheight));
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($tmp, $source, 0, 0, 0, 0, $tmpwidth, $tmpheight, $width, $height);
    imagecopyresampled($thumb, $tmp, 0, 0, $mid[0]-$mid[2], $mid[1]-$mid[3], $newwidth, $newheight, $newwidth, $newheight);
    imagedestroy($tmp);
  # No cropping - distort if needed
  } else {
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
  }
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
