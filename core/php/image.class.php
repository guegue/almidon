<?php

if(!defined('IMG_QUALITY')) define('IMG_QUALITY',85);

class almImage {
  function almImage() {
  } 

  function resize($file, $w, $h = null, $restrict = true) {
    if ($w&&$file) {
      if(is_string($file))
        $image = imagecreatefromstring(file_get_contents($file));
      elseif(is_resource($file)) $image = &$file;
      $cur_w = imagesx($image);
      $cur_h = imagesy($image);
      # Si no se pasa el ALTO se calcula
      $is_calc_h = false;
      if (!$h) {
        $h = ceil($cur_h*($w/$cur_w));
        $is_calc_h = true;
      }
      # Si la imagen es más ancha, de lo contrario es más alta, claro esto solo se ejecuta si restrict es true, de lo contrario la imagen se estira.
      if ($restrict) {
        if ($cur_w>$cur_h) $h = ceil($cur_h*($w/$cur_w));
        elseif(!$is_calc_h) $w = ceil($cur_w*($h/$cur_h));
      }
      # se prepara el area del gráfico con el ancho y alto definido
      $new_img = imagecreatetruecolor ($w, $h);
      imagecopyresampled($new_img, $image, 0, 0, 0, 0, $w, $h, $cur_w, $cur_h);
      return $new_img;
    } else return false;
  }

  function crop($file, $w, $h, $pos = 'center') {
    if ($w&$h) {
      if(is_string($file))
        $image = imagecreatefromstring(file_get_contents($file));
      elseif(is_resource($file)) $image = &$file;
      $cur_w = imagesx($image);
      $cur_h = imagesy($image);
      # Variables que almacenan las dimensiones de la imagen para hacer resize antes de hacer crop, se inicializan con los valores de $w y $h
      $res_w = $w;
      $res_h = $h;
      # Calculo el alto y ancho que tendria que tener la imagen para posteriormente hacer el crop
      if ($w==$h)     {
        if ($cur_w>$cur_h) {
          $res_w = ceil($cur_w*($h/$cur_h));
        }  else    $res_h = ceil($cur_h*($w/$cur_w));
      } elseif ($w>$h)  {
        if ($cur_w>$cur_h) {
          $tmp_h = ceil($cur_h*($w/$cur_w));
          if($tmp_h < $h) $res_w = ceil($cur_w*($h/$cur_h));
          else $res_h = $tmp_h;
        } else {
          $tmp_w = ceil($cur_w*($h/$cur_h));
          if($tmp_w < $w) $res_h = ceil($cur_h*($w/$cur_w));
          else $res_w = $tmp_w;
        }
      } else {
        if ($cur_w>$cur_h)  {
          $tmp_w = ceil($cur_w*($h/$cur_h));
          if($tmp_w > $w) $res_w = $tmp_w;
        } else {
           $tmp_h = ceil($cur_h*($w/$cur_w));
           if($tmp_h > $h) $res_h = $tmp_h;
        }
      }
      switch($pos) {
        case 'center':
          $cropLeft = ($res_w/2) - ($w/2);
          $cropTop = ($res_h/2) - ($h/2);
          break;
        case 'left':
          $cropLeft = 0;
          $cropTop = 0;
          break;
        case 'right':
          $cropLeft = $res_w - $w;
          $cropTop = $res_h - $h;
          break;
        default:
         list($cropLeft, $cropTop) = preg_split('/,/', $pos);
         $cropLeft = (int)trim($cropLeft);
         $cropTop = (int)trim($cropTop);
       }

       $cropLeft = (int)$cropLeft;
       $cropTop = (int)$cropTop;
       $image = $this->resize($image,$res_w,$res_h);
       $new_img = imagecreatetruecolor ($w, $h);
       /*
        *       Hace el resize de la imagen pero copia desde las cordenadas x = cropLeft y las cordenadas y = cropTop
        *       y con la región que comprende el ancho = $res_w y alto = $res_h, en una imagen nueva.
        */
      
       imagecopyresampled($new_img, $image, 0, 0, $cropLeft, $cropTop, $res_w, $res_h, $res_w, $res_h);
       return $new_img;
    } else return false;
  }

  function rounded($file,$radius) {
    # Require Corner, RGB and Tools classes
    require_once 'Rounded/RGB.php';
    require_once 'Rounded/Corner.php';
    require_once 'Rounded/Tools.php';

    $params = array(
    'radius' => $radius,
    'orientation' => 'tl',
    'foreground' => 0,
    'background' => 'fff',
    'borderwidth' => 0,
    'bordercolor' => 0,
    'bgtransparent' => false,
    'fgtransparent' => true,
    'btransparent' => true,
    'antialias' => true
    );
    if($file) {
      if(is_string($file))
        $image = imagecreatefromstring(file_get_contents($file));
      elseif(is_resource($file)) $image = &$file;
      $img = Rounded_Corner::create($params);
      imagecopy($image, $img, 0, 0, 0, 0, $radius, $radius);
      $img = Rounded_Tools::imageFlipVertical($img);
      imagecopy($image, $img, 0, imagesy($image) - $radius, 0, 0, $radius,$radius);
      $img = Rounded_Tools::imageFlipHorizontal($img);
      imagecopy($image, $img, imagesx($image) - $radius, imagesy($image) - $radius, 0, 0, $radius, $radius);
      $img = Rounded_Tools::imageFlipVertical($img);
      imagecopy($image, $img, imagesx($image) - $radius, 0, 0, 0, $radius, $radius);
      return $image;
    }
  }
}

?>
