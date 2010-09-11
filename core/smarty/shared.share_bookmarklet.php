<?php
/**
 * Smarty shared plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * video_service common function
 *
 * Function: smarty_function_video_services<br>
 * Purpose:  used by other smarty functions to return
 *           an array of video's service
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param void
 * @return array
 */

/* Reserved TAGs */
/*
 [url]: Address of the Page
 [title]: Page's title
 [desc]: Page's description
 */
function smarty_function_social_network_codes($key=null) {
    $arr_network = array(
      'google' => array('label' => 'Google Bookmarks','url' => 'http://www.google.com/bookmarks/mark?op=edit&output=popup&bkmk=[url]','image' => 'google.gif'),
      'meneame' => array('label' => 'Menéame','url' => 'http://meneame.net/submit.php?url=[url]','image' => 'meneame.gif'),
      'fresqui' => array('label' => 'Fresqui','url' => 'http://act.fresqui.com/post?url=[url]&title=[title]','image' => 'fresqui.gif'),
      'del.icio.us' => array('label' => 'Del.icio.us','url' => 'http://del.icio.us/post?url=[url]&title=[title]','image' => 'delicious.png'),
      'facebook' => array('label' => 'Facebook','url' => 'http://www.facebook.com/share.php?u=[url]&t=[title]','image' => 'facebook.gif','js_events'=>"onclick=\"u=location.href;t=document.title;window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');return false;\""),
      'myspace' => array('label' => 'MySpace','url' => 'http://www.myspace.com/Modules/PostTo/Pages/?t=[title]&c=[desc]&u=[url]','image' => 'myspace.png')
    );
    if(!$key)
      return $arr_network;
    else {
      # You can use a name or a number to call the info
      switch($key) {
        case 'delicious':
          $network = $arr_network['del.icio.us']; 
          break;
        default:
          if(is_int($key)) {
            $i=1;
            foreach($arr_network as $net) {
              if($i==$key) {
                $network = $net;
		break;
              }
              $i++;
            }
          } else
            $network = $arr_network[$key];
      }
      return $network;
    }
}

function smarty_function_social_networks()
{
    $arr_service = smarty_function_social_network_codes();
    $list_service = array();
    foreach($arr_service as $key => $value)
      $list_service[$key] = $arr_service[$key]['label'];
    return $list_service;
}

/* vim: set expandtab: */
?>
